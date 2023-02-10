<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/tasks')->name('home');
Route::redirect('/dashboard', '/tasks')->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::resource('tasks', \App\Http\Controllers\TaskController::class)
        ->withTrashed(['destroy']);


    Route::put('tasks/{task}/toggle', [\App\Http\Controllers\TaskController::class, 'toggle'])
        ->name('tasks.toggle');
    Route::put('tasks/{task}/restore', [\App\Http\Controllers\TaskController::class, 'restore'])
        ->withTrashed()
        ->name('tasks.restore');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
