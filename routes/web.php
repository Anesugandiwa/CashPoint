<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PagesController;
Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/Dashboard', [PagesController::class, 'index'])->name('dasboard');
Route::get('/dashboard', [PagesController::class, 'dash'])->name('dash');
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
