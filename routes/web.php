<?php

use App\Models\Car;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
