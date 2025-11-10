<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\User;
use App\Models\Otp;
use App\Core\Mailer;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', [
            'csrf' => Csrf::token(),
        ]);
    }

    public function login(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? '')) {
            http_response_code(422);
            echo 'Invalid CSRF token';
            return;
        }

        $email = Validator::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            Auth::login($user);
            header('Location: ' . \url_to('dashboard'));
            exit;
        }

        $this->view('auth/login', [
            'csrf' => Csrf::token(),
            'error' => 'Invalid credentials provided.',
        ]);
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . \url_to('login'));
        exit;
    }

    public function showRegister(): void
    {
        $this->view('auth/register', [
            'csrf' => Csrf::token(),
        ]);
    }

    public function register(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? '')) {
            http_response_code(422);
            echo 'Invalid CSRF token';
            return;
        }

        $data = [
            'name' => Validator::sanitize($_POST['name'] ?? ''),
            'email' => Validator::sanitize($_POST['email'] ?? ''),
            'phone' => Validator::sanitize($_POST['phone'] ?? ''),
            'password' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
            'role' => User::ROLE_MEMBER,
            'organization_id' => null,
        ];

        if (!Validator::required($data['name']) || !Validator::email($data['email'])) {
            $this->view('auth/register', [
                'csrf' => Csrf::token(),
                'error' => 'Please provide valid information.',
            ]);
            return;
        }

        $userModel = new User();
        if ($userModel->findByEmail($data['email'])) {
            $this->view('auth/register', [
                'csrf' => Csrf::token(),
                'error' => 'An account already exists with this email address.',
            ]);
            return;
        }

        Auth::user();
        $_SESSION['pending_registration'] = $data;
        $otpCode = random_int(100000, 999999);
        $otpModel = new Otp();
        $otpModel->create($data['email'], (string) $otpCode);
        $this->logOtp($data['email'], $otpCode);

        $mailer = new Mailer();
        $subject = 'KSRA MIS Registration OTP';
        $body = sprintf(
            "Dear %s,%s%s%s%sRegards,%sKSRA MIS Team",
            $data['name'] ?: 'Member',
            PHP_EOL,
            'Use the following One-Time Password to complete your registration: ' . $otpCode,
            PHP_EOL . PHP_EOL,
            'This OTP will expire in 10 minutes.',
            PHP_EOL . PHP_EOL
        );
        $mailSent = $mailer->send($data['email'], $subject, $body);
        $message = $mailSent
            ? 'An OTP has been sent to your email address. Please enter it below to complete registration.'
            : 'An OTP has been generated. Email delivery is unavailable, please use the OTP from the logs to complete registration.';

        $this->view('auth/verify', [
            'csrf' => Csrf::token(),
            'email' => $data['email'],
            'message' => $message,
        ]);
    }

    public function verify(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? '')) {
            http_response_code(422);
            echo 'Invalid CSRF token';
            return;
        }

        $email = Validator::sanitize($_POST['email'] ?? '');
        $code = Validator::sanitize($_POST['code'] ?? '');
        $otpModel = new Otp();

        Auth::user();
        if (empty($_SESSION['pending_registration']) || $_SESSION['pending_registration']['email'] !== $email) {
            $this->view('auth/login', [
                'csrf' => Csrf::token(),
                'error' => 'Registration session expired. Please register again.',
            ]);
            return;
        }

        if (!$otpModel->validate($email, $code)) {
            $this->view('auth/verify', [
                'csrf' => Csrf::token(),
                'email' => $email,
                'error' => 'Invalid or expired OTP. Please request a new one.',
            ]);
            return;
        }

        $userModel = new User();
        $pending = $_SESSION['pending_registration'];
        unset($_SESSION['pending_registration']);

        $userModel->create($pending);
        $user = $userModel->findByEmail($pending['email']);
        if (isset($user['password'])) {
            unset($user['password']);
        }

        Auth::login($user);
        header('Location: ' . \url_to('dashboard'));
        exit;
    }

    private function logOtp(string $email, int $otp): void
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }
        $logMessage = sprintf('[%s] OTP for %s: %s%s', date('Y-m-d H:i:s'), $email, $otp, PHP_EOL);
        file_put_contents($logDir . '/otp.log', $logMessage, FILE_APPEND);
    }
}
