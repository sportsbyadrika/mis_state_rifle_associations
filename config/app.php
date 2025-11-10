<?php
return [
    'name' => 'Kerala State Rifle Association MIS',
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN),
    'url' => getenv('APP_URL') ?: 'http://localhost',
    'key' => getenv('APP_KEY'),
    'hash_salt' => getenv('HASH_SALT') ?: 'change_this_salt',
    'session_name' => getenv('SESSION_NAME') ?: 'ksra_session',
];
