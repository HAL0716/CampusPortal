<?php

return [
    'constraints' => [
        'email' => 'users_email_unique',
    ],
    'unique_columns' => [
        'email' => 'users.email',
    ],
];
