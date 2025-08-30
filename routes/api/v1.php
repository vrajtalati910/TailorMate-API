<?php

use App\Http\Controllers\Api\v1\CustomerController;
use App\Http\Controllers\Api\v1\ItemController;
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

    //Customers
    Route::group(['prefix' => 'customers'], function () { 
        Route::get('/', [CustomerController::class, 'list']);
        Route::post('/create', [CustomerController::class, 'create']);
        Route::post('/update/{customer}', [CustomerController::class, 'update']);
    });

    //Items
    Route::group(['prefix' => 'items'], function () { 
        Route::get('/', [ItemController::class, 'list']);
        Route::post('/create', [ItemController::class, 'create']);
        Route::post('/update/{item}', [ItemController::class, 'update']);
        Route::get('details/{item}', [ItemController::class, 'show']);
    });
});
