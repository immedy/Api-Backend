<?php

use App\Http\Controllers\Authentication\AuthMobileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthMobileController::class)->group(function(){
    Route::post('login','login');
});