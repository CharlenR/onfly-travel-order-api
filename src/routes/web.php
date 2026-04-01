<?php

use Illuminate\Support\Facades\Route;

/**
 * Basic GET route returning a view
 * Path: /
 */
Route::get('/', function () {
    return view('welcome');
});