<?php

use App\Http\Controllers\Api\v1\CustomerController;
use App\Http\Controllers\Api\v1\ItemController;
use App\Http\Controllers\Api\v1\MeasurementController;
use App\Http\Controllers\Api\v1\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return 'Database migrated successfully!';
});

Route::get('/migrate-fresh', function () {
    Artisan::call('migrate:fresh');
    Artisan::call('db:seed --class=UserSeeder');
    return 'Database migrated successfully!';
});

Route::get('/seed', function () {
    Artisan::call('db:seed');
    return 'Database seeded successfully!';
});

Route::get('/migrate-fresh-seed-whole', function () {
    Artisan::call('migrate:fresh --seed');
    return 'Database migrated and seeded successfully!';
});

Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return 'Optimize cleared successfully!';
});

Route::get('/get-all-users', function () {
    return User::all();
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully!';
});

Route::post('/login', [UserController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {

    //Measurements
    Route::group(['prefix' => 'measurements'], function () { 
        Route::get('/', [MeasurementController::class, 'list']);
        Route::post('/create', [MeasurementController::class, 'create']);
        Route::post('/update/{measurement}', [MeasurementController::class, 'update']);
    });

    //Customers
    Route::group(['prefix' => 'customers'], function () { 
        Route::get('/', [CustomerController::class, 'list']);
        Route::post('/create', [CustomerController::class, 'create']);
        Route::post('/update/{customer}', [CustomerController::class, 'update']);
        Route::get('details/{customer}', [CustomerController::class, 'show']);

        Route::post('/add-item/{customer}', [CustomerController::class, 'addItem']);
        Route::post('/update-item/{customerItems}', [CustomerController::class,'updateItem']);
        Route::get('/items-details/{customerItems}', [CustomerController::class,'itemsDetails']);
    });

    //Items
    Route::group(['prefix' => 'items'], function () { 
        Route::get('/', [ItemController::class, 'list']);
        Route::post('/create', [ItemController::class, 'create']);
        Route::post('/update/{item}', [ItemController::class, 'update']);
        Route::get('details/{item}', [ItemController::class, 'show']);
    });
});
