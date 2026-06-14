<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});

require __DIR__.'/auth.php';

require __DIR__.'/app.php';

require __DIR__.'/admin.php';
