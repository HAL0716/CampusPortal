<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])
            ->name('users');
        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');
        Route::get('/users/export/{file}', [UserController::class, 'export'])
            ->middleware('signed')
            ->name('users.export');
    });
