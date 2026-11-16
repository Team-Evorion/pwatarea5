<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {

    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    // Si no ha iniciado sesión, enviar al login.
    if ($user === null) {
        return redirect()->route('login');
    }

    // Determinar el dashboard según el rol.
    $dashboard = match ($user->role?->name) {
        'Administrator' => 'dashboard.administrator',
        'Librarian' => 'dashboard.librarian',
        'Reader' => 'dashboard.reader',
        default => null,
    };

    // Rechazar usuarios sin un rol válido.
    abort_if(
        $dashboard === null,
        403,
        'Tu cuenta no tiene un rol válido.'
    );

    return redirect()->route($dashboard);
});


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


    // Funciones exclusivas del administrador
    Route::middleware('role:Administrator')->group(function () {

        // Eliminar libros
        Route::delete('/books/{book}', [BookController::class, 'destroy'])
            ->name('books.destroy');

        // Gestión de usuarios
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        // Actualizar rol de un usuario
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])
            ->name('users.role.update');

    });

});

// Dashboard del administrador
Route::middleware(['auth', 'role:Administrator'])
    ->get(
        '/dashboard/administrator',
        [DashboardController::class, 'administrator']
    )
    ->name('dashboard.administrator');

// Dashboard del bibliotecario
Route::middleware(['auth', 'role:Librarian'])
    ->get(
        '/dashboard/librarian',
        [DashboardController::class, 'librarian']
    )
    ->name('dashboard.librarian');

// Dashboard del lector
Route::middleware(['auth', 'role:Reader'])
    ->get(
        '/dashboard/reader',
        [DashboardController::class, 'reader']
    )
    ->name('dashboard.reader');
