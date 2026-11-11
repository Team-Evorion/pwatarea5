<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(10);
        return view('books.index', compact('books'));
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
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Libro eliminado con éxito.');
    }
}