
@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="mb-4">
                <a href="{{ route('books.index') }}"
                   class="text-decoration-none">
                    ← Volver al catálogo
                </a>
            </div>

            <div class="card book-form-card border-0 shadow-sm">

                <div class="book-form-header p-4">
                    <h1 class="h3 fw-bold mb-1">
                        Editar libro
                    </h1>
                    <p class="mb-0 text-white-50">
                        Actualiza la información de {{ $book->title }}.
                    </p>
                </div>

                <div class="card-body p-4 p-md-5">

                    <form action="{{ route('books.update', $book) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="title" class="form-label">
                                    Título
                                </label>
                                <input type="text"
                                       id="title"
                                       name="title"
                                       maxlength="50"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $book->title) }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="author" class="form-label">
                                    Autor
                                </label>
                                <input type="text"
                                       id="author"
                                       name="author"
                                       maxlength="50"
                                       class="form-control @error('author') is-invalid @enderror"
                                       value="{{ old('author', $book->author) }}"
                                       required>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="genre" class="form-label">
                                    Género literario
                                </label>
                                <input type="text"
                                       id="genre"
                                       name="genre"
                                       maxlength="50"
                                       class="form-control @error('genre') is-invalid @enderror"
                                       value="{{ old('genre', $book->genre) }}">
                                @error('genre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="quantity" class="form-label">
                                    Cantidad total de ejemplares
                                </label>
                                <input type="number"
                                       id="quantity"
                                       name="quantity"
                                       min="{{ $book->activeLoansCount() }}"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       value="{{ old('quantity', $book->quantity) }}"
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-text">
                                    Préstamos activos: {{ $book->activeLoansCount() }}.
                                    No puedes reducir el stock por debajo de esa cantidad.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="year_number" class="form-label">
                                    Año de la obra o edición
                                </label>
                                <input type="number"
                                       id="year_number"
                                       name="year_number"
                                       min="1"
                                       max="9999"
                                       class="form-control @error('year_number') is-invalid @enderror"
                                       value="{{ old('year_number', $book->year !== null ? abs((int) $book->year) : '') }}">
                                @error('year_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="year_era" class="form-label">
                                    Era histórica
                                </label>
                                <select id="year_era"
                                        name="year_era"
                                        class="form-select @error('year_era') is-invalid @enderror"
                                        required>
                                    <option value="DC"
                                        @selected(old('year_era', (int) $book->year < 0 ? 'AC' : 'DC') === 'DC')>
                                        Después de Cristo (d. C.)
                                    </option>

                                    <option value="AC"
                                        @selected(old('year_era', (int) $book->year < 0 ? 'AC' : 'DC') === 'AC')>
                                        Antes de Cristo (a. C.)
                                    </option>
                                </select>
                                @error('year_era')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="cover" class="form-label">
                                    Portada del libro
                                </label>

                                @if($book->cover_path)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $book->cover_path) }}"
                                             alt="Portada actual de {{ $book->title }}"
                                             class="book-current-cover rounded-3 border">

                                        <p class="small text-muted mt-2 mb-0">
                                            Portada actual
                                        </p>
                                    </div>
                                @else
                                    <p class="text-muted small">
                                        Este libro todavía no tiene portada.
                                    </p>
                                @endif

                                <input type="file"
                                       id="cover"
                                       name="cover"
                                       accept=".jpg,.jpeg,.png,.webp"
                                       class="form-control @error('cover') is-invalid @enderror">

                                @error('cover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-text">
                                    Deja este campo vacío para conservar la imagen.
                                    JPG, PNG o WebP. Máximo 2 MB.
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                Guardar cambios
                            </button>

                            <a href="{{ route('books.index') }}"
                               class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
