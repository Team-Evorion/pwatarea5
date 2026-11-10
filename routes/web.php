<?php

use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $nombre = 'Biblioteca Online';
    
    return view('biblioteca', ['nombre' => $nombre]);
});

Route::get('/biblioteca', function () {
    return 'Bienvenido al Sistema de Biblioteca Online';
});


Route::middleware('auth')->group(function () {

    Route::post(
        '/prestamos',
        [LoanController::class, 'store']
    )->name('prestamos.store');

    Route::patch(
        '/prestamos/{prestamoId}/devolver',
        [LoanController::class, 'update']
    )->name('prestamos.devolver');

});
