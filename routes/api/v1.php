<?php

use App\Http\Controllers\Api\v1\MeasurementController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    //Measurements
    Route::group(['prefix' => 'measurements'], function () { 
        Route::get('/', [MeasurementController::class, 'list']);
        Route::post('/create', [MeasurementController::class, 'create']);
        Route::put('/update/{measurement}', [MeasurementController::class, 'update']);
    });
});
