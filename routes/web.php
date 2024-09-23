<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactosController;

Route::get('/contactos', [ContactosController::class, 'index'])->name('contactos.index');
Route::post('/contactos', [ContactosController::class, 'store'])->name('contactos.store');

Route::get('/', function () {
    return view('welcome');
});
