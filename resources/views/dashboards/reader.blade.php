@extends('layouts.app')
@section('title', 'Mi biblioteca · Biblioteca PWA')
@section('content')
<section class="library-hero mt-3 mb-4">
    <div class="row align-items-center g-4 position-relative" style="z-index:1">
        <div class="col-lg-8">
            <span class="hero-pill"><i class="bi bi-stars" aria-hidden="true"></i> TU RINCÓN DE LECTURA</span>
            <h1 class="mt-3 mb-2">Una nueva historia te espera, {{ Auth::user()->username }}.</h1>
            <p class="mb-0">Descubre los títulos de la biblioteca y consulta tus préstamos en cualquier momento.</p>
        </div>
        <div class="col-lg-4"><div class="hero-actions justify-content-lg-end"><a href="{{ route('books.index') }}" class="btn btn-light"><i class="bi bi-search me-2" aria-hidden="true"></i>Explorar libros</a></div></div>
    </div>
</section>
<div class="page-section-heading"><h2>Mi actividad</h2><small>Estadísticas de tu cuenta</small></div>
<div class="row g-3">
    <div class="col-12 col-md-4"><div class="metric-card"><div class="metric-top"><span class="metric-title">Títulos disponibles para explorar</span><span class="metric-icon"><i class="bi bi-bookshelf" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($booksCount) }}</div><div class="metric-footer">En nuestro catálogo</div></div></div>
    <div class="col-12 col-md-4"><div class="metric-card"><div class="metric-top"><span class="metric-title">Mis préstamos activos</span><span class="metric-icon amber"><i class="bi bi-journal-arrow-up" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($myActiveLoans) }}</div><div class="metric-footer">Pendientes de devolución</div></div></div>
    <div class="col-12 col-md-4"><div class="metric-card"><div class="metric-top"><span class="metric-title">Libros devueltos</span><span class="metric-icon mint"><i class="bi bi-check2-circle" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($myReturnedLoans) }}</div><div class="metric-footer">En tu historial personal</div></div></div>
</div>
<div class="page-section-heading"><h2>¿Qué quieres hacer hoy?</h2><small>Tu biblioteca personal</small></div>
<div class="row g-3">
    <div class="col-md-4"><a href="{{ route('books.index') }}" class="quick-card"><span class="quick-card-icon"><i class="bi bi-collection" aria-hidden="true"></i></span><h3>Explorar catálogo</h3><p>Encuentra obras, autores y disponibilidad de ejemplares.</p><span class="quick-link">Descubrir libros <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
    <div class="col-md-4"><a href="{{ route('transactions.index', ['status' => 'borrowed']) }}" class="quick-card"><span class="quick-card-icon amber"><i class="bi bi-bookmark" aria-hidden="true"></i></span><h3>Mis préstamos</h3><p>Consulta los libros que tienes pendientes de devolución.</p><span class="quick-link">Ver préstamos <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
    <div class="col-md-4"><a href="{{ route('transactions.index', ['status' => 'returned']) }}" class="quick-card"><span class="quick-card-icon mint"><i class="bi bi-clock-history" aria-hidden="true"></i></span><h3>Mi historial</h3><p>Revisa los títulos que ya has devuelto y sus fechas.</p><span class="quick-link">Consultar historial <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
</div>
<div class="alert alert-info mt-4" role="note"><i class="bi bi-info-circle me-2" aria-hidden="true"></i>Para solicitar un préstamo, consulta la disponibilidad y acude al personal bibliotecario.</div>
@endsection
