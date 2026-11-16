@extends('layouts.app')
@section('title', 'Bibliotecario · Biblioteca PWA')
@section('content')
<section class="library-hero mt-3 mb-4">
    <div class="row align-items-center g-4 position-relative" style="z-index:1">
        <div class="col-lg-8">
            <span class="hero-pill"><i class="bi bi-bookmark-heart" aria-hidden="true"></i> ESPACIO DEL BIBLIOTECARIO</span>
            <h1 class="mt-3 mb-2">Hola, {{ Auth::user()->username }}</h1>
            <p class="mb-0">Organiza los ejemplares disponibles, registra préstamos y acompaña a tus lectores.</p>
        </div>
        <div class="col-lg-4"><div class="hero-actions justify-content-lg-end"><a class="btn btn-light" href="{{ route('transactions.create') }}"><i class="bi bi-plus-circle me-2" aria-hidden="true"></i>Nuevo préstamo</a></div></div>
    </div>
</section>
<div class="page-section-heading"><h2>Resumen de operaciones</h2><small>Información en tiempo real</small></div>
<div class="row g-3">
    <div class="col-12 col-md-4"><div class="metric-card"><div class="metric-top"><span class="metric-title">Títulos registrados</span><span class="metric-icon"><i class="bi bi-bookshelf" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($booksCount) }}</div><div class="metric-footer">En el catálogo</div></div></div>
    <div class="col-12 col-md-4"><div class="metric-card"><div class="metric-top"><span class="metric-title">Ejemplares disponibles</span><span class="metric-icon mint"><i class="bi bi-check-circle" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($availableCopies) }}</div><div class="metric-footer">Listos para préstamo</div></div></div>
    <div class="col-12 col-md-4"><div class="metric-card"><div class="metric-top"><span class="metric-title">Préstamos activos</span><span class="metric-icon amber"><i class="bi bi-journal-arrow-up" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($activeLoansCount) }}</div><div class="metric-footer">Pendientes de devolución</div></div></div>
</div>
<div class="page-section-heading"><h2>Herramientas de trabajo</h2><small>Gestiona el día a día</small></div>
<div class="row g-3">
    <div class="col-sm-6 col-xl-3"><a href="{{ route('books.index') }}" class="quick-card"><span class="quick-card-icon"><i class="bi bi-collection" aria-hidden="true"></i></span><h3>Ver catálogo</h3><p>Revisa las obras disponibles y los ejemplares registrados.</p><span class="quick-link">Abrir catálogo <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
    <div class="col-sm-6 col-xl-3"><a href="{{ route('books.create') }}" class="quick-card"><span class="quick-card-icon mint"><i class="bi bi-plus-square" aria-hidden="true"></i></span><h3>Agregar libro</h3><p>Registra nuevas obras e incorpora sus portadas.</p><span class="quick-link">Registrar libro <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
    <div class="col-sm-6 col-xl-3"><a href="{{ route('transactions.index', ['status' => 'borrowed']) }}" class="quick-card"><span class="quick-card-icon amber"><i class="bi bi-journal-bookmark" aria-hidden="true"></i></span><h3>Préstamos activos</h3><p>Consulta los ejemplares que aún no han sido devueltos.</p><span class="quick-link">Revisar préstamos <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
    <div class="col-sm-6 col-xl-3"><a href="{{ route('transactions.create') }}" class="quick-card"><span class="quick-card-icon violet"><i class="bi bi-arrow-left-right" aria-hidden="true"></i></span><h3>Nuevo préstamo</h3><p>Asigna un libro disponible a un lector registrado.</p><span class="quick-link">Registrar préstamo <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
</div>
@endsection
