<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
Route::redirect('/', '/books');

// Rutas de invitados
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
 // Listado de préstamos para los tres roles
    Route::middleware('role:Administrator,Librarian,Reader')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');
    });

    // Solo administrador y bibliotecario pueden registrar préstamos
    Route::middleware('role:Administrator,Librarian')->group(function () {
        Route::get('/transactions/create', [TransactionController::class, 'create'])
            ->name('transactions.create');

        Route::post('/transactions', [TransactionController::class, 'store'])
            ->name('transactions.store');
    Route::patch('/transactions/{transaction}/return', [TransactionController::class, 'returnBook'])
    ->name('transactions.return');
});
    // Administrador y bibliotecario pueden registrar y editar libros
    Route::middleware('role:Administrator,Librarian')->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    });

    // Solo el administrador puede eliminar libros
    Route::middleware('role:Administrator')->group(function () {
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    });
});
/*
|--------------------------------------------------------------------------
| Dashboard del Administrador
|--------------------------------------------------------------------------
| Acceso exclusivo para usuarios con rol Administrator.
*/

Route::middleware(['auth', 'role:Administrator'])
    ->get('/dashboard/administrator', function () {
        return view('dashboards.administrator');
    })
    ->name('dashboard.administrator');
    /*
|--------------------------------------------------------------------------
| Dashboard del Bibliotecario
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Librarian'])
    ->get('/dashboard/librarian', function () {
        return view('dashboards.librarian');
    })
    ->name('dashboard.librarian');
    /*
|--------------------------------------------------------------------------
| Dashboard del Lector
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Reader'])
    ->get('/dashboard/reader', function () {
        return view('dashboards.reader');
    })
    ->name('dashboard.reader');