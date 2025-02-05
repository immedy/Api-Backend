<?php

use App\Http\Controllers\Api\Mobile\AbsensiController;
use App\Http\Controllers\Authentication\AuthMobileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthMobileController::class)->group(function(){
    Route::post('login','login');

});

Route::controller(AbsensiController::class)->group(function(){
    route::get('checkAutentikasi','getUser');
    route::post('postAbsenMasuk','scanQRcodeAbsenMasuk');
    route::post('postAbsenPulang','scanQRcodeAbsenPulang');
    route::post('addDeviceId','addDeviceId');
    route::get('listEmployee','listEmployee');
});