<?php

use Illuminate\Support\Facades\Route;

/**
 * Root endpoint returning API information
 * Path: /
 */
Route::get('/', function () {
    return response()->json([
        'name' => 'Onfly Travel Order API',
        'version' => '1.0.0',
        'description' => 'API for managing travel orders and approvals',
        'status' => 'online',
        'documentation' => url('/api/documentation'),
    ]);
});