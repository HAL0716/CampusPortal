<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'home' : 'login');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
