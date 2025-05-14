<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerDashboardController;
Route::group([
    'middleware' => [App\Http\Middleware\is_Customer::class,]

], function(){
    Route::get('/customerDashboard',[CustomerDashboardController::class,'index'])->name('customer_index');

});


