<?php

namespace App\Core;

class Mailer
{
    public function send(string $to, string $subject, string $body): bool
    {
        $driver = config('mail.driver', 'log');
        $fromAddress = config('mail.from_address', 'no-reply@example.com');
        $fromName = config('mail.from_name', 'KSRA MIS');

        if ($driver === 'mail') {
            $headers = sprintf(
                "From: %s <%s>\r\nReply-To: %s\r\nX-Mailer: PHP/%s",
                $fromName,
                $fromAddress,
                $fromAddress,
                phpversion()
            );

            $result = @mail($to, $subject, $body, $headers);
            if ($result) {
                return true;
            }
        }

        $this->logEmail($to, $subject, $body, isset($result) ? (bool) $result : false);
        return $driver === 'log' ? true : false;
    }

    private function logEmail(string $to, string $subject, string $body, bool $sent): void
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        $status = $sent ? 'SENT' : 'LOGGED';
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
