<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
   public function index(Request $request)
{
    // Obtener el texto ingresado por el usuario
    $search = trim($request->input('search', ''));

    // Preparar la consulta de libros
    $query = Book::query();

    // Aplicar el filtro si existe una búsqueda
    if ($search !== '') {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('author', 'like', '%' . $search . '%')
              ->orWhere('genre', 'like', '%' . $search . '%');
        });
    }

    // Obtener resultados paginados
    $books = $query->orderBy('title')
                   ->paginate(10)
                   ->withQueryString();

    // Enviar resultados a la vista
    return view('books.index', compact('books', 'search'));
}

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:50',
            'author'   => 'required|string|max:50',
            'year'     => 'nullable|integer|min:1000|max:' . date('Y'),
            'genre'    => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
        ]);

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Libro creado exitosamente.');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:50',
            'author'   => 'required|string|max:50',
            'year'     => 'nullable|integer|min:1000|max:' . date('Y'),
            'genre'    => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Libro actualizado exitosamente.');
    }

    public function destroy(Book $book)
    {
        if ($book->activeLoansCount() > 0) {
            return redirect()->route('books.index')
                ->with('error', 'No se puede eliminar el libro "' . $book->title . '" porque tiene préstamos activos sin devolver.');
        }

        $book->delete();
        return redirect()->route('books.index')->with('success', 'Libro eliminado con éxito.');
    }
}