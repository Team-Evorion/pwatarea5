@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Registrar préstamo</h2>

        <a href="{{ route('transactions.index') }}"
           class="btn btn-outline-secondary">
            Volver a préstamos
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <h5 class="mb-4">Datos del préstamo</h5>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="user_id" class="form-label">
                        Seleccionar lector
                    </label>

                    <select name="user_id"
                            id="user_id"
                            class="form-select"
                            required>
                        <option value="">Seleccione un lector</option>

                        @foreach($readers as $reader)
                            <option value="{{ $reader->id }}"
                                @selected(old('user_id') == $reader->id)>
                                {{ $reader->username }} - {{ $reader->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="book_id" class="form-label">
                        Seleccionar libro
                    </label>

                    <select name="book_id"
                            id="book_id"
                            class="form-select"
                            required>
                        <option value="">Seleccione un libro</option>

                        @foreach($books as $book)
                            <option value="{{ $book->id }}"
                                @selected(old('book_id') == $book->id)>
                                {{ $book->title }}
                                ({{ $book->availableCopies() }} disponibles)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Fecha del préstamo
                    </label>

                    <input type="date"
                           class="form-control"
                           value="{{ date('Y-m-d') }}"
                           readonly>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        Registrar préstamo
                    </button>

                    <a href="{{ route('transactions.index') }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection