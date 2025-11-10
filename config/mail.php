<?php
return [
    'driver' => getenv('MAIL_DRIVER') ?: 'log',
    'from_address' => getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@example.com',
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'KSRA MIS',
    'host' => getenv('MAIL_HOST') ?: null,
    'port' => getenv('MAIL_PORT') !== false ? (int) getenv('MAIL_PORT') : 587,
    'username' => ($value = getenv('MAIL_USERNAME')) !== false && $value !== '' && strtolower($value) !== 'null' ? $value : null,
    'password' => ($value = getenv('MAIL_PASSWORD')) !== false && $value !== '' && strtolower($value) !== 'null' ? $value : null,
    'encryption' => getenv('MAIL_ENCRYPTION') ?: 'tls',
    'timeout' => getenv('MAIL_TIMEOUT') !== false ? (int) getenv('MAIL_TIMEOUT') : 10,
];
