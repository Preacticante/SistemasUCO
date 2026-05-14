<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    return "Validando datos...";
})->name('login.post');