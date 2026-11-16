@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <!-- Encabezado -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Panel del Lector</h1>
            <p class="text-muted mb-0">
                Bienvenido, {{ Auth::user()->username }}
            </p>
        </div>

        <span class="badge bg-info text-dark fs-6">
            Lector
        </span>
    </div>

    <!-- Tarjetas principales -->
    <div class="row g-4">

        <!-- Catálogo -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold">📚 Catálogo de libros</h5>
                    <p class="text-muted">
                        Explora los libros disponibles
                        en nuestra biblioteca.
                    </p>
                    <a href="{{ route('books.index') }}"
                       class="btn btn-primary">
                        Explorar catálogo
                    </a>
                </div>
            </div>
        </div>

        <!-- Mis préstamos -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold">📖 Mis préstamos</h5>
                    <p class="text-muted">
                        Consulta los libros que tienes
                        actualmente prestados.
                    </p>
                   <a href="{{ route('transactions.index') }}"
   class="btn btn-primary">
    Mis préstamos
</a>
                </div>
            </div>
        </div>

        <!-- Historial -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold">📋 Historial</h5>
                    <p class="text-muted">
                        Revisa tus préstamos y
                        devoluciones anteriores.
                    </p>
                    <button class="btn btn-outline-secondary"
                            disabled>
                        Próximamente
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Información adicional -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h4 class="fw-bold">Mi Biblioteca</h4>
            <p class="text-muted mb-0">
                Desde este espacio podrás explorar
                el catálogo y consultar los servicios
                disponibles para lectores.
            </p>
        </div>
    </div>

</div>

@endsection