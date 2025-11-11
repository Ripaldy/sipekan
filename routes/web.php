<?php

use Illuminate\Support\Facades\Route;

// Serve React Frontend - catch all routes except /api
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
