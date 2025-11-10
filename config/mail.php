<?php
return [
    'driver' => getenv('MAIL_DRIVER') ?: 'log',
    'from_address' => getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@example.com',
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'KSRA MIS',
];
