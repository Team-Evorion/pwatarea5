@extends('layouts.app')
@section('title', 'Catálogo · Biblioteca PWA')
@section('content')
@php
    $role = Auth::user()->role?->name;
    $canManageBooks = in_array($role, ['Administrator', 'Librarian'], true);
    $isAdministrator = $role === 'Administrator';
@endphp

<section class="catalog-hero mt-3 mb-4 p-4 p-lg-5">
    <div class="row align-items-center g-3 position-relative" style="z-index:1">
        <div class="col-lg-8">
            <span class="catalog-eyebrow"><i class="bi bi-stars me-2" aria-hidden="true"></i>EXPLORA NUESTRA COLECCIÓN</span>
            <h1 class="fw-bold mt-3 mb-2">Historias que merecen ser descubiertas.</h1>
            <p class="mb-0">Encuentra tu próxima lectura, descubre autores y conoce qué libros están disponibles.</p>
        </div>
        @if($canManageBooks)
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('books.create') }}" class="btn btn-light"><i class="bi bi-plus-circle me-2" aria-hidden="true"></i>Nuevo libro</a>
            </div>
        @endif
    </div>
</section>

<form action="{{ route('books.index') }}" method="GET" class="catalog-search mb-4" role="search">
    <div class="catalog-search-icon" aria-hidden="true"><i class="bi bi-search"></i></div>
    <label class="visually-hidden" for="search">Buscar por título, autor o género</label>
    <input id="search" type="search" name="search" class="catalog-search-input" value="{{ $search ?? '' }}" placeholder="Busca un libro, un autor o un género...">
    <button type="submit" class="btn btn-primary">Buscar</button>
    @if(!empty($search))
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Limpiar</a>
    @endif
</form>

<div class="page-section-heading">
    <div><h2>Catálogo de libros</h2><small>Libros disponibles en nuestra colección</small></div>
    <span class="results-pill"><i class="bi bi-collection me-2" aria-hidden="true"></i>{{ $books->total() }} {{ $books->total() === 1 ? 'título' : 'títulos' }}</span>
</div>

<div class="row g-4">
@forelse($books as $book)
    @php
        $available = max(0, (int) $book->quantity - (int) $book->active_loans_count);
    @endphp
    <div class="col-12 {{ $books->total() === 1 ? '' : 'col-xl-6' }}">
        <article class="book-tile">
            <div class="book-tile-art">
                @if($book->cover_path)
                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="Portada de {{ $book->title }}" loading="lazy">
                @else
                    <div class="book-tile-placeholder"><i class="bi bi-book-half" aria-hidden="true"></i><span>Sin portada</span></div>
                @endif
            </div>
            <div class="book-tile-info">
                <div class="book-tile-topline"><span class="book-genre">{{ $book->genre ?: 'Literatura general' }}</span></div>
                <h3 class="book-tile-title">{{ $book->title }}</h3>
                <div class="book-tile-author"><i class="bi bi-person me-1" aria-hidden="true"></i>{{ $book->author }}</div>
                <div class="book-tile-date"><i class="bi bi-calendar3 me-1" aria-hidden="true"></i>{{ $book->formatted_year }}</div>
                <div class="book-tile-spacer"></div>
                <div class="book-availability">
                    @if($available > 0)
                        <span class="availability-good"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Disponible</span>
                    @else
                        <span class="availability-bad"><i class="bi bi-x-circle-fill" aria-hidden="true"></i> Sin ejemplares</span>
                    @endif
                    <span class="available-count">{{ $available }} de {{ $book->quantity }} ejemplares</span>
                </div>
                @if($canManageBooks)
                    <div class="book-tile-actions">
                        <a href="{{ route('books.edit', $book) }}" class="btn btn-primary"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Editar libro</a>
                        @if($isAdministrator)
                            <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este libro?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash me-1" aria-hidden="true"></i>Eliminar</button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="book-request-hint"><i class="bi bi-info-circle" aria-hidden="true"></i> {{ $available > 0 ? 'Solicita este libro al personal bibliotecario.' : 'Consulta más adelante por nuevos ejemplares.' }}</div>
                @endif
            </div>
        </article>
    </div>
@empty
    <div class="col-12"><div class="empty-collection"><i class="bi bi-search" aria-hidden="true"></i><h3>No encontramos libros con esa búsqueda</h3><p>Prueba otro título, autor o género.</p><a href="{{ route('books.index') }}" class="btn btn-outline-primary">Ver toda la colección</a></div></div>
@endforelse
</div>

@if($books->hasPages())
    <div class="d-flex justify-content-center mt-4">{{ $books->links('pagination::bootstrap-5') }}</div>
@endif
@endsection
