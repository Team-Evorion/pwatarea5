@extends('layouts.app')
@section('title', 'Administración · Biblioteca PWA')
@section('content')
<section class="library-hero mt-3 mb-4">
    <div class="row align-items-center g-4 position-relative" style="z-index:1">
        <div class="col-lg-8">
            <span class="hero-pill"><i class="bi bi-stars" aria-hidden="true"></i> CENTRO DE ADMINISTRACIÓN</span>
            <h1 class="mt-3 mb-2">Bienvenido, {{ Auth::user()->username }} <span aria-hidden="true">👋</span></h1>
            <p class="mb-0">Toda tu biblioteca, en un mismo lugar. Gestiona el catálogo, las personas y los préstamos de forma sencilla.</p>
        </div>
        <div class="col-lg-4">
            <div class="hero-actions justify-content-lg-end">
                <a class="btn btn-light" href="{{ route('books.create') }}"><i class="bi bi-plus-circle me-2" aria-hidden="true"></i>Agregar libro</a>
                <a class="btn btn-outline-light" href="{{ route('transactions.create') }}">Nuevo préstamo</a>
            </div>
        </div>
    </div>
</section>

<div class="page-section-heading"><h2>Resumen general</h2><small>Datos actualizados de la biblioteca</small></div>
<div class="row g-3">
    <div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-top"><span class="metric-title">Títulos registrados</span><span class="metric-icon"><i class="bi bi-bookshelf" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($booksCount) }}</div><div class="metric-footer">Libros en catálogo</div></div></div>
    <div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-top"><span class="metric-title">Usuarios</span><span class="metric-icon violet"><i class="bi bi-people" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($usersCount) }}</div><div class="metric-footer">Cuentas registradas</div></div></div>
    <div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-top"><span class="metric-title">Préstamos activos</span><span class="metric-icon amber"><i class="bi bi-journal-arrow-up" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($activeLoansCount) }}</div><div class="metric-footer">Pendientes de devolución</div></div></div>
    <div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-top"><span class="metric-title">Devueltos</span><span class="metric-icon mint"><i class="bi bi-check2-circle" aria-hidden="true"></i></span></div><div class="metric-value">{{ number_format($returnedLoansCount) }}</div><div class="metric-footer">Préstamos finalizados</div></div></div>
</div>

<div class="page-section-heading"><h2>Herramientas de gestión</h2><small>Accede rápidamente a cada módulo</small></div>
<div class="row g-3">
    <div class="col-sm-6 col-xl-3"><a href="{{ route('books.index') }}" class="quick-card"><span class="quick-card-icon"><i class="bi bi-collection" aria-hidden="true"></i></span><h3>Catálogo de libros</h3><p>Consulta títulos, edita información y revisa el inventario.</p><span class="quick-link">Explorar catálogo <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
    <div class="col-sm-6 col-xl-3"><a href="{{ route('books.create') }}" class="quick-card"><span class="quick-card-icon mint"><i class="bi bi-plus-square" aria-hidden="true"></i></span><h3>Registrar libro</h3><p>Incorpora nuevas obras, ejemplares y portadas.</p><span class="quick-link">Agregar libro <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
    <div class="col-sm-6 col-xl-3"><a href="{{ route('users.index') }}" class="quick-card"><span class="quick-card-icon violet"><i class="bi bi-people" aria-hidden="true"></i></span><h3>Usuarios y roles</h3><p>Administra cuentas y permisos de acceso al sistema.</p><span class="quick-link">Gestionar usuarios <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
    <div class="col-sm-6 col-xl-3"><a href="{{ route('transactions.index') }}" class="quick-card"><span class="quick-card-icon amber"><i class="bi bi-arrow-left-right" aria-hidden="true"></i></span><h3>Préstamos y devoluciones</h3><p>Consulta el historial y el estado de cada préstamo.</p><span class="quick-link">Ver préstamos <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span></a></div>
</div>
@endsection
