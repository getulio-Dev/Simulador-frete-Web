<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreteController;

Route::get('/', function () {
    return redirect('/simular');
});

Route::get('/simular', [FreteController::class, 'index'])->name('formulario');

Route::get('/simulador', function () {
    return view('simulador');
});

Route::post('/simular', [FreteController::class, 'calcular'])->name('simular');
