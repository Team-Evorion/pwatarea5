@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h3 fw-bold mb-0">Catálogo de Libros</h2>
    @if(Auth::user()->role && Auth::user()->role->name !== 'Reader')
        <a href="{{ route('books.create') }}" class="btn btn-primary">+ Nuevo Libro</a>
    @endif
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Género</th>
                    <th>Año</th>
                    <th>Stock</th>
                    @if(Auth::user()->role && Auth::user()->role->name !== 'Reader')
                        <th class="text-center">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td class="fw-semibold">{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td><span class="badge bg-info text-dark">{{ $book->genre ?? 'General' }}</span></td>
                        <td>{{ $book->year }}</td>
                        <td>
                            @php $disponibles = $book->availableCopies(); @endphp
                            <span class="badge {{ $disponibles > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $disponibles }} / {{ $book->quantity }} disponibles
                            </span>
                        </td>
                        @if(Auth::user()->role && Auth::user()->role->name !== 'Reader')
                            <td class="text-center">
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este libro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No hay libros registrados en el catálogo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $books->links('pagination::bootstrap-5') }}
</div>
@endsection