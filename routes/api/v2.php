<?php

use Illuminate\Support\Facades\Route;

Route::get('test', function () {
     return __('messages.test2');
});