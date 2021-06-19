<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/get-history', [App\Http\Controllers\HistoricoController::class, 'index'])->name('get-history');

Auth::routes();

Route::middleware(['auth'])->group(function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('/pontos', 'App\Http\Controllers\PontosController');
    Route::resource('/funcoes', 'App\Http\Controllers\RolesController');
    Route::resource('/users', 'App\Http\Controllers\UsersController');
    Route::resource('/status', 'App\Http\Controllers\StatusController');
    Route::resource('/historico', 'App\Http\Controllers\HistoricoController');
    Route::resource('/reset-password', 'App\Http\Controllers\ResetPasswordController');
});
