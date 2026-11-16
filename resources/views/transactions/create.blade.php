@extends('layouts.app')
@section('title', 'Registrar préstamo · Biblioteca PWA')
@section('content')
<div class="data-page-intro mt-3 mb-4"><div><span class="page-kicker"><i class="bi bi-arrow-left-right me-1" aria-hidden="true"></i> MOVIMIENTO DE LIBROS</span><h1 class="page-title">Registrar nuevo préstamo</h1><p class="page-description">Selecciona un lector y un libro con ejemplares disponibles.</p></div><a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver</a></div>
<div class="row g-4">
    <div class="col-lg-8"><div class="content-panel p-4 p-md-5">
        <div class="mb-4"><h2 class="h5 fw-bold">Datos del préstamo</h2><p class="small text-muted mb-0">La fecha se asigna automáticamente cuando registres el préstamo.</p></div>
        @if($errors->any())<div class="alert alert-danger" role="alert"><strong>Revisa los datos:</strong><ul class="mt-2 mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('transactions.store') }}">@csrf
            <div class="mb-4"><label for="user_id" class="form-label">Lector</label><select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required><option value="">Selecciona una cuenta de lector</option>@foreach($readers as $reader)<option value="{{ $reader->id }}" @selected(old('user_id') == $reader->id)>{{ $reader->username }} — {{ $reader->email }}</option>@endforeach</select>@error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-4"><label for="book_id" class="form-label">Libro disponible</label><select id="book_id" name="book_id" class="form-select @error('book_id') is-invalid @enderror" required><option value="">Selecciona un libro</option>@foreach($books as $book)<option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>{{ $book->title }} ({{ $book->availableCopies() }} disponibles)</option>@endforeach</select>@error('book_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-4"><label for="loan_date" class="form-label">Fecha de registro (automática)</label><input type="date" id="loan_date" class="form-control" value="{{ now()->toDateString() }}" disabled></div>
            <div class="d-flex flex-wrap gap-2"><button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-2" aria-hidden="true"></i>Confirmar préstamo</button><a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Cancelar</a></div>
        </form>
    </div></div>
    <aside class="col-lg-4"><div class="info-aside"><span class="quick-card-icon mint"><i class="bi bi-shield-check" aria-hidden="true"></i></span><h3>Préstamo seguro</h3><p>El sistema comprueba automáticamente que haya ejemplares disponibles y que el lector no tenga ya un préstamo activo del mismo libro.</p><div class="small text-muted"><i class="bi bi-info-circle me-1" aria-hidden="true"></i>Solo bibliotecarios y administradores pueden registrar préstamos.</div></div></aside>
</div>
@endsection
