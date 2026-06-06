<?php

use App\Http\Controllers\BusinessController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BusinessController::class, 'index'])->name('home');
Route::get('/negocio/{slug}', [BusinessController::class, 'show'])->name('businesses.show');
Route::get('/categoria/{slug}', [BusinessController::class, 'byCategory'])->name('categories.show');
