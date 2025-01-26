<?php

use App\Http\Controllers\API\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index']);
    //Route::get('/search', [NewsController::class, 'search']);
    //Route::get('/filters', [NewsController::class, 'filter']);
});
