<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cookieController;

Route::get('/{any}', function () {
    return view('index');
})->where('any','.*');