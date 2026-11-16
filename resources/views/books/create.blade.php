
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
                        Registrar nuevo libro
                    </h1>
                    <p class="mb-0 text-white-50">
                        Completa los datos y agrega una portada opcional.
                    </p>
                </div>

                <div class="card-body p-4 p-md-5">

                    <form action="{{ route('books.store') }}"
                        method="POST"
                        enctype="multipart/form-data">
                        @csrf

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
                                    value="{{ old('title') }}"
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
                                    value="{{ old('author') }}"
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
                                    value="{{ old('genre') }}"
                                    placeholder="Ej. Épica, novela, poesía">
                                @error('genre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="quantity" class="form-label">
                                    Cantidad de ejemplares
                                </label>
                                <input type="number"
                                    id="quantity"
                                    name="quantity"
                                    min="0"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity', 1) }}"
                                    required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    value="{{ old('year_number') }}"
                                    placeholder="Ej. 800">
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
                                        @selected(old('year_era', 'DC') === 'DC')>
                                        Después de Cristo (d. C.)
                                    </option>
                                    <option value="AC"
                                        @selected(old('year_era') === 'AC')>
                                        Antes de Cristo (a. C.)
                                    </option>
                                </select>
                                @error('year_era')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-text">
                                    Para una obra antigua puedes registrar un año
                                    aproximado. Ejemplo: 800 a. C.
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="cover" class="form-label">
                                    Portada del libro
                                </label>

                                <input type="file"
                                    id="cover"
                                    name="cover"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    class="form-control @error('cover') is-invalid @enderror">

                                @error('cover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-text">
                                    Opcional. JPG, PNG o WebP. Máximo 2 MB.
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                Guardar libro
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
