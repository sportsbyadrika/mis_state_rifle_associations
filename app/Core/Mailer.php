<?php

namespace App\Core;

class Mailer
{
    public function send(string $to, string $subject, string $body): bool
    {
        $driver = strtolower((string) config('mail.driver', 'log'));
        $fromAddress = $this->sanitizeHeaderValue((string) config('mail.from_address', 'no-reply@example.com'));
        $fromName = $this->sanitizeHeaderValue((string) config('mail.from_name', 'KSRA MIS'));
        $recipient = $this->sanitizeHeaderValue($to);
        $cleanSubject = $this->sanitizeHeaderValue($subject);

        switch ($driver) {
            case 'smtp':
                $sent = $this->sendViaSmtp($recipient, $cleanSubject, $body, $fromAddress, $fromName);
                $this->logEmail($recipient, $cleanSubject, $body, $sent ? 'SENT' : 'FAILED');
                return $sent;
            case 'mail':
                $headers = sprintf(
                    "From: %s <%s>\r\nReply-To: %s\r\nX-Mailer: PHP/%s",
                    $fromName,
                    $fromAddress,
                    $fromAddress,
                    phpversion()
                );

                $result = @mail($recipient, $cleanSubject, $body, $headers);
                $this->logEmail($recipient, $cleanSubject, $body, $result ? 'SENT' : 'FAILED');
                return (bool) $result;
            case 'log':
            default:
                $this->logEmail($recipient, $cleanSubject, $body, 'LOGGED');
                return true;
        }
    }

    private function sendViaSmtp(string $to, string $subject, string $body, string $fromAddress, string $fromName): bool
    {
        $host = config('mail.host');
        $port = (int) config('mail.port', 587);
        $username = config('mail.username');
        $password = config('mail.password');
        $encryption = strtolower((string) config('mail.encryption', 'tls'));
        $timeout = (int) config('mail.timeout', 10);

        if (!$host || !$port) {
            return false;
        }

        $targetHost = $encryption === 'ssl' ? 'ssl://' . $host : $host;
        $context = null;

        if ($encryption === 'tls') {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
        }

        $socket = @stream_socket_client(
            $targetHost . ':' . $port,
            $errno,
            $errstr,
            $timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$socket) {
            return false;
        }

        stream_set_timeout($socket, $timeout);

        $success = $this->expectResponse($socket, 220)
            && $this->sendCommand($socket, 'EHLO ' . $this->determineEhloHost(), 250);

        if ($success && $encryption === 'tls') {
            $success = $this->sendCommand($socket, 'STARTTLS', 220)
                && @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)
                && $this->sendCommand($socket, 'EHLO ' . $this->determineEhloHost(), 250);
        }

        if (!$success) {
            fclose($socket);
            return false;
        }

        if ($username && $password) {
            $success = $this->sendCommand($socket, 'AUTH LOGIN', 334)
                && $this->sendCommand($socket, base64_encode($username), 334)
                && $this->sendCommand($socket, base64_encode($password), 235);

            if (!$success) {
                fclose($socket);
                return false;
            }
        }

        $headers = $this->buildHeaders($to, $subject, $fromAddress, $fromName);
        $messageBody = $this->normalizeNewLines($body);

        $success = $this->sendCommand($socket, 'MAIL FROM:<' . $fromAddress . '>', 250)
            && $this->sendCommand($socket, 'RCPT TO:<' . $to . '>', 250, 251, 252)
            && $this->sendCommand($socket, 'DATA', 354);

        if (!$success) {
            fclose($socket);
            return false;
        }

        $data = $headers . "\r\n" . $messageBody . "\r\n.";
        $dataSuccess = fwrite($socket, $data . "\r\n") !== false && $this->expectResponse($socket, 250);
        $this->sendCommand($socket, 'QUIT', 221);
        fclose($socket);

        return $dataSuccess;
    }

    private function buildHeaders(string $to, string $subject, string $fromAddress, string $fromName): string
    {
        $headers = [
            'Date: ' . date(DATE_RFC2822),
            'From: ' . $fromName . ' <' . $fromAddress . '>',
            'To: ' . $to,
            'Subject: ' . $subject,
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
        ];

        return implode("\r\n", $headers) . "\r\n";
    }

    private function normalizeNewLines(string $text): string
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", $text);
        $normalized = str_replace("\n.", "\n..", $normalized);
        return str_replace("\n", "\r\n", $normalized);
    }

    private function determineEhloHost(): string
    {
        $host = gethostname();
        return $host ?: 'localhost';
    }

    private function sendCommand($socket, string $command, int ...$expectedCodes): bool
    {
        if (fwrite($socket, $command . "\r\n") === false) {
            return false;
        }

        return $this->expectResponse($socket, ...$expectedCodes);
    }

    private function expectResponse($socket, int ...$expectedCodes): bool
    {
        $response = $this->readResponse($socket);
        if ($response === null) {
            return false;
        }

        $code = (int) substr($response, 0, 3);
        return in_array($code, $expectedCodes, true);
    }

    private function readResponse($socket): ?string
    {
        $response = '';
        while (($line = fgets($socket, 512)) !== false) {
            $response .= $line;
            if (strlen($line) >= 4 && $line[3] === ' ') {
                break;
            }
        }

        return $response === '' ? null : $response;
    }

    private function sanitizeHeaderValue(string $value): string
    {
        return trim(str_replace(["\r", "\n"], '', $value));
    }

    private function logEmail(string $to, string $subject, string $body, string $status): void
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        $message = sprintf(
            "[%s] [%s] To: %s | Subject: %s%s%s%s%s",
            date('Y-m-d H:i:s'),
            $status,
            $to,
            $subject,
            PHP_EOL,
            $body,
            PHP_EOL,
            str_repeat('-', 40) . PHP_EOL
        );

        file_put_contents($logDir . '/mail.log', $message, FILE_APPEND);
    }
}
