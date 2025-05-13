<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function() {
    // Route::get('/Dashboard', [PagesController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [PagesController::class, 'dash'])->name('dash');

    // super Admin Routes
Route::get('/user', [UserController::class, 'index'])->name('manage_staff');
Route::get('/loanManagement', [UserController::class, 'loan'])->name('manage_loan');
Route::get('/loanForm', [UserController::class, 'form'])->name('loan');

});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
