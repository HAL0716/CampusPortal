<?php

use App\Http\Controllers\Admin\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/appoint', [TeacherController::class, 'appoint'])->name('teachers.appoint');
    Route::post('/teachers/appoint', [TeacherController::class, 'store'])->name('teachers.store');
    Route::patch('/teachers/{teacher}/resign', [TeacherController::class, 'resign'])->name('teachers.resign');
});
