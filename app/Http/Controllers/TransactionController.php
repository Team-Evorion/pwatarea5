<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\LoanService;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
{
    // Obtener el usuario autenticado
    $user = Auth::user();

    // Obtener el filtro seleccionado
    $status = $request->query('status', 'all');

    // Validar el estado recibido
    if (!in_array($status, ['all', 'borrowed', 'returned'], true)) {
        $status = 'all';
    }

    // Preparar la consulta de préstamos
    $query = Transaction::with(['user', 'book'])
        ->orderBy('created_at', 'desc');

    // Los lectores solo pueden consultar sus préstamos
    if ($user->role?->name === 'Reader') {
        $query->where('user_id', $user->id);
    }

    // Mostrar préstamos pendientes de devolución
    if ($status === 'borrowed') {
        $query->whereNull('date_of_return');
    }

    // Mostrar préstamos devueltos
    if ($status === 'returned') {
        $query->whereNotNull('date_of_return');
    }

    // Paginar y conservar el filtro seleccionado
    $transactions = $query->paginate(10)->withQueryString();

    return view('transactions.index', compact('transactions', 'status'));
}
    public function create()
{
    // Solo administradores y bibliotecarios
    // pueden acceder al formulario.
    abort_unless(
        in_array(Auth::user()->role?->name, [
            'Administrator',
            'Librarian'
        ], true),
        403
    );

    // Obtener únicamente los usuarios lectores.
    $readers = User::whereHas('role', function ($query) {
        $query->where('name', 'Reader');
    })->orderBy('username')->get();

    // Obtener libros que tengan ejemplares disponibles.
    $books = Book::orderBy('title')->get()
        ->filter(function ($book) {
            return $book->availableCopies() > 0;
        });

    return view('transactions.create', compact('readers', 'books'));
}
    public function store(Request $request, LoanService $loanService)
    {
        abort_unless(
            in_array(Auth::user()->role?->name, [
                'Administrator',
                'Librarian'
            ], true),
            403
        );

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id',
        ]);

        $reader = User::with('role')->findOrFail($validated['user_id']);

        if ($reader->role?->name !== 'Reader') {
            return back()
                ->withErrors([
                    'user_id' => 'El usuario seleccionado debe ser un lector.'
                ])
                ->withInput();
        }

        $loanService->prestarLibro(
            (int) $validated['user_id'],
            (int) $validated['book_id']
        );

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Préstamo registrado correctamente.');
    }
    public function returnBook(
        Transaction $transaction,
        LoanService $loanService
    ) {
        abort_unless(
            in_array(Auth::user()->role?->name, [
                'Administrator',
                'Librarian'
            ], true),
            403
        );

        $loanService->devolverLibro(
            (int) $transaction->id,
            (int) Auth::id(),
            true
        );

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Libro devuelto correctamente.');
    }
}
