@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <!-- Encabezado -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Panel del Bibliotecario</h1>
            <p class="text-muted mb-0">
                Bienvenido, {{ Auth::user()->username }}
            </p>
        </div>

        <span class="badge bg-success fs-6">
            Bibliotecario
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
                        Consulta los libros disponibles
                        en nuestra biblioteca.
                    </p>
                    <a href="{{ route('books.index') }}"
                       class="btn btn-primary">
                        Ver catálogo
                    </a>
                </div>
            </div>
        </div>

        <!-- Registrar libros -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold">➕ Registrar libros</h5>
                    <p class="text-muted">
                        Registra nuevos ejemplares
                        en el catálogo.
                    </p>
                    <a href="{{ route('books.create') }}"
                       class="btn btn-success">
                        Agregar libro
                    </a>
                </div>
            </div>
        </div>

        <!-- Préstamos -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold">📖 Préstamos</h5>
                    <p class="text-muted">
                        Gestiona préstamos y devoluciones
                        de libros.
                    </p>
                    <a href="{{ route('transactions.index') }}"
   class="btn btn-primary">
    Ver préstamos
</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Información -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h4 class="fw-bold">Gestión Bibliotecaria</h4>
            <p class="text-muted mb-0">
                Desde este panel podrás consultar el
                catálogo y acceder a las herramientas
                autorizadas para bibliotecarios.
            </p>
        </div>
    </div>

</div>

@endsection