<?php
use App\Http\Controllers\AuthController; use Illuminate\Support\Facades\Route;
Route::middleware('guest')->group(function(){Route::get('/login',[AuthController::class,'loginForm'])->name('login');Route::post('/login',[AuthController::class,'login'])->middleware('throttle:5,1')->name('login.store');Route::get('/registro',[AuthController::class,'registerForm'])->name('register');Route::post('/registro',[AuthController::class,'register'])->middleware('throttle:10,1')->name('register.store');});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
