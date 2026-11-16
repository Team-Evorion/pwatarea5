<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $query = Book::query()->withCount([
            'transactions as active_loans_count' =>
                fn ($query) => $query->whereNull('date_of_return'),
        ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('genre', 'like', '%' . $search . '%');
            });
        }

        $books = $query->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        return view('books.index', compact('books', 'search'));
    }

    public function create()
    {
        return view('books.create');
    }

    // Registrar libro y portada
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'author' => 'required|string|max:50',
            'year_number' => 'nullable|integer|between:1,9999',
            'year_era' => 'required|in:AC,DC',      
            'genre' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:2048',
        ]);

        // Convertir el año histórico al formato de la base de datos.
        $yearNumber = $validated['year_number'] ?? null;
        $era = $validated['year_era'];

        // Evitar fechas futuras en nuestra era.
        if (
            $yearNumber !== null &&
            $era === 'DC' &&
            (int) $yearNumber > now()->year
        ) {
            throw ValidationException::withMessages([
                'year_number' => 'El año no puede ser posterior al actual.',
            ]);
        }

        $validated['year'] = $yearNumber === null
            ? null
            : (int) $yearNumber * ($era === 'AC' ? -1 : 1);

        // Estos campos pertenecen al formulario, no a la tabla books.
        unset(
            $validated['year_number'],
            $validated['year_era'],
            $validated['cover']
        );

        // El archivo no se guarda directamente en MySQL.

        $newCoverPath = null;

        try {
            if ($request->hasFile('cover')) {
                $newCoverPath = $request->file('cover')
                    ->store('book-covers', 'public');

                if (!$newCoverPath) {
                    throw new \RuntimeException(
                        'No se pudo guardar la portada.'
                    );
                }

                $validated['cover_path'] = $newCoverPath;
            }

            Book::create($validated);

        } catch (\Throwable $e) {
            // Limpiar la imagen si falla el registro del libro.
            if ($newCoverPath) {
                Storage::disk('public')->delete($newCoverPath);
            }

            throw $e;
        }

        return redirect()
            ->route('books.index')
            ->with('success', 'Libro creado exitosamente.');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    // Actualizar datos y reemplazar portada opcionalmente
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'author' => 'required|string|max:50',
            'year_number' => 'nullable|integer|between:1,9999',
            'year_era' => 'required|in:AC,DC',      
            'genre' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:2048',
        ]);

        // Convertir el año histórico al formato de la base de datos.
        $yearNumber = $validated['year_number'] ?? null;
        $era = $validated['year_era'];

        // Evitar fechas futuras en nuestra era.
        if (
            $yearNumber !== null &&
            $era === 'DC' &&
            (int) $yearNumber > now()->year
        ) {
            throw ValidationException::withMessages([
                'year_number' => 'El año no puede ser posterior al actual.',
            ]);
        }

        $validated['year'] = $yearNumber === null
            ? null
            : (int) $yearNumber * ($era === 'AC' ? -1 : 1);

        // Estos campos pertenecen al formulario, no a la tabla books.
        unset(
            $validated['year_number'],
            $validated['year_era'],
            $validated['cover']
        );

        $newCoverPath = null;
        $oldCoverPath = null;

        try {
            if ($request->hasFile('cover')) {
                $newCoverPath = $request->file('cover')
                    ->store('book-covers', 'public');

                if (!$newCoverPath) {
                    throw new \RuntimeException(
                        'No se pudo guardar la portada.'
                    );
                }

                $validated['cover_path'] = $newCoverPath;
            }

            DB::transaction(function () use (
                $book,
                $validated,
                &$oldCoverPath
            ) {
                $lockedBook = Book::query()
                    ->whereKey($book->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Conservar la protección del inventario.
                $activeLoans = $lockedBook->transactions()
                    ->whereNull('date_of_return')
                    ->count();

                if ((int) $validated['quantity'] < $activeLoans) {
                    throw ValidationException::withMessages([
                        'quantity' =>
                            "No puedes establecer {$validated['quantity']} ejemplares: hay {$activeLoans} prestados actualmente.",
                    ]);
                }

                if (array_key_exists('cover_path', $validated)) {
                    $oldCoverPath = $lockedBook->cover_path;
                }

                $lockedBook->update($validated);

            }, 3);

        } catch (\Throwable $e) {
            // Si la actualización falla, eliminar la imagen nueva.
            if ($newCoverPath) {
                Storage::disk('public')->delete($newCoverPath);
            }

            throw $e;
        }

        // Una vez actualizado el libro, eliminar la portada anterior.
        if (
            $newCoverPath &&
            $oldCoverPath &&
            str_starts_with($oldCoverPath, 'book-covers/')
        ) {
            Storage::disk('public')->delete($oldCoverPath);
        }

        return redirect()
            ->route('books.index')
            ->with('success', 'Libro actualizado exitosamente.');
    }

    public function destroy(Book $book)
    {
        $coverPath = null;

        $deleted = DB::transaction(function () use (
            $book,
            &$coverPath
        ) {
            $lockedBook = Book::query()
                ->whereKey($book->id)
                ->lockForUpdate()
                ->firstOrFail();

            // No eliminar libros que tengan historial de préstamos.
            if ($lockedBook->transactions()->exists()) {
                return false;
            }

            $coverPath = $lockedBook->cover_path;
            $lockedBook->delete();

            return true;

        }, 3);

        if (!$deleted) {
            return redirect()
                ->route('books.index')
                ->with(
                    'error',
                    'No se puede eliminar el libro porque tiene un historial de préstamos.'
                );
        }

        // Limpiar la portada cuando se elimina el libro.
        if (
            $coverPath &&
            str_starts_with($coverPath, 'book-covers/')
        ) {
            Storage::disk('public')->delete($coverPath);
        }

        return redirect()
            ->route('books.index')
            ->with('success', 'Libro eliminado con éxito.');
    }
}
