<?php

use App\Controllers\HomeController;
use App\Controllers\MovieController;
use Kernel\Router\Route;

return [
    Route::get('/home', [HomeController::class, 'index']),
    Route::get('/movie', [MovieController::class, 'index']),
    Route::get('/admin/movie/add', [MovieController::class, 'create']),
    Route::post('/admin/movie/add', [MovieController::class, 'store']),
];
