<?php

use Illuminate\Support\Facades\Route;
use App\Repositories\DataSource\DataSourceRepositoryInterface;

Route::get('/', function () {
    return view('welcome');
});

