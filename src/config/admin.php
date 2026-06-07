<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin User Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default admin user credentials. These will be
    | used to create an admin user when you run the database seeder. Make
    | sure to change these values in your .env file for security reasons.
    |
    */

    'name' => env('ADMIN_NAME', 'Admin'),
    'email' => env('ADMIN_EMAIL', 'admin@school.com'),
    'password' => env('ADMIN_PASSWORD', 'password'),
];
