<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;




Route::group([
    'middleware' => [App\Http\Middleware\Is_Admin::class,]
], function(){
    Route::get('/dashboard', [PagesController::class, 'dash'])->name('dash');
    Route::get('/Asset',[PagesController::class,'asset'])->name('asset_index');
    Route::get('/user', [UserController::class, 'index'])->name('manage_staff');
    Route::get('/loanManagement', [UserController::class, 'loan'])->name('manage_loan');
    Route::get('/loanForm', [UserController::class, 'form'])->name('loan');
});