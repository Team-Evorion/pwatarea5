@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">Agregar Nuevo Libro</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('books.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Título</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Autor</label>
                        <input type="text" id="author" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" required>
                        @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="year" class="form-label">Año de Publicación</label>
                            <input type="number" id="year" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year') }}">
                            @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Cantidad (Stock)</label>
                            <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" required min="0">
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="genre" class="form-label">Género</label>
                        <input type="text" id="genre" name="genre" class="form-control @error('genre') is-invalid @enderror" value="{{ old('genre') }}">
                        @error('genre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Libro</button>
                    <a href="{{ route('books.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection