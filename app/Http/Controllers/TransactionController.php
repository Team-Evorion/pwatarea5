<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Transaction::with(['user', 'book'])
            ->orderBy('created_at', 'desc');

        if ($user->role?->name === 'Reader') {
            $query->where('user_id', $user->id);
        }

        $transactions = $query->paginate(10);

        return view('transactions.index', compact('transactions'));
    }
    public function create()
{
    // Solo administradores y bibliotecarios
    // pueden acceder al formulario.
    abort_unless(
        in_array(auth()->user()->role?->name, [
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
    public function store(Request $request)
    {
        abort_unless(
            in_array(auth()->user()->role?->name, [
                'Administrator',
                'Librarian'
            ], true),
            403
        );

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id',
        ]);

        $reader = User::with('role')
            ->findOrFail($validated['user_id']);

        if ($reader->role?->name !== 'Reader') {
            return back()
                ->withErrors([
                    'user_id' => 'El usuario seleccionado debe ser un lector.'
                ])
                ->withInput();
        }

        $registered = DB::transaction(function () use ($validated) {
            $book = Book::findOrFail($validated['book_id']);

            if ($book->availableCopies() <= 0) {
                return false;
            }

            Transaction::create([
                'user_id' => $validated['user_id'],
                'book_id' => $book->id,
                'date_of_issue' => now()->toDateString(),
                'date_of_return' => null,
            ]);

            return true;
        });

        if (!$registered) {
            return back()
                ->withErrors([
                    'book_id' => 'Este libro no tiene ejemplares disponibles.'
                ])
                ->withInput();
        }

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Préstamo registrado correctamente.');
    }
     public function returnBook(Transaction $transaction)
    {
        // Solo el administrador y el bibliotecario
        // pueden registrar devoluciones.
        abort_unless(
            in_array(auth()->user()->role?->name, [
                'Administrator',
                'Librarian'
            ], true),
            403
        );

        // Evitar devolver dos veces el mismo préstamo.
        if ($transaction->date_of_return !== null) {
            return redirect()
                ->route('transactions.index')
                ->with('error', 'Este préstamo ya fue devuelto.');
        }

        // Registrar la fecha de devolución.
        $transaction->update([
            'date_of_return' => now()->toDateString(),
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Libro devuelto correctamente.');
    }
}
