<?php

use App\Http\Controllers\BusinessController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BusinessController::class, 'index'])->name('home');
Route::get('/negocios/{slug}', [BusinessController::class, 'show'])->name('businesses.show');
