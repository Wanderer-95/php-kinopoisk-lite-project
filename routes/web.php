<?php

use App\Controllers\AdminController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\MovieController;
use App\Controllers\RegisterController;
use Kernel\Router\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/login', [LoginController::class, 'login']),
    Route::post('/login', [LoginController::class, 'store']),
    Route::get('/register', [RegisterController::class, 'register']),
    Route::post('/register', [RegisterController::class, 'store']),
    Route::post('/logout', [LoginController::class, 'logout']),
    Route::get('/admin', [AdminController::class, 'index']),
    Route::get('/admin/categories/add', [CategoryController::class, 'create']),
    Route::post('/admin/categories/add', [CategoryController::class, 'store']),
    Route::get('/admin/categories/update', [CategoryController::class, 'edit']),
    Route::post('/admin/categories/update', [CategoryController::class, 'update']),
    Route::post('/admin/categories/destroy', [CategoryController::class, 'destroy']),
    Route::get('/admin/movies/add', [MovieController::class, 'create']),
    Route::post('/admin/movies/add', [MovieController::class, 'store']),
];
