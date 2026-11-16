@extends('layouts.app')
@section('content')

<div class="container-fluid py-4">

    <!-- Encabezado -->
    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1">
                Panel de Administración
            </h1>
            <p class="text-muted mb-0">
                Bienvenido,
                {{ Auth::user()->username }}
            </p>
        </div>

        <span class="badge bg-primary fs-6">
            Administrador
        </span>
    </div>

    <!-- Tarjetas del dashboard -->
    <div class="row g-4">

        <!-- Libros -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        📚 Catálogo de libros
                    </h5>
                    <p class="card-text text-muted">
                        Consulta y administra los libros
                        registrados en la biblioteca.
                    </p>
                    <a href="{{ route('books.index') }}"
                       class="btn btn-primary">
                        Ver catálogo
                    </a>
                </div>
            </div>
        </div>

        <!-- Agregar libros -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        ➕ Registrar libros
                    </h5>
                    <p class="card-text text-muted">
                        Incorpora nuevos libros al
                        catálogo de la biblioteca.
                    </p>
                    <a href="{{ route('books.create') }}"
                       class="btn btn-success">
                        Agregar libro
                    </a>
                </div>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        👥 Usuarios
                    </h5>
                    <p class="card-text text-muted">
                        Administración de usuarios
                        y roles del sistema.
                    </p>
                    <button class="btn btn-outline-secondary"
                            disabled>
                        Próximamente
                    </button>
                </div>
            </div>
        </div>

        <!-- Préstamos -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        📖 Préstamos
                    </h5>
                    <p class="card-text text-muted">
                        Consulta y seguimiento de
                        préstamos y devoluciones.
                    </p>
                   <a href="{{ route('transactions.index') }}"
   class="btn btn-primary">
    Ver préstamos
</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Información adicional -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h4 class="fw-bold">
                Sistema de Gestión de Biblioteca
            </h4>
            <p class="text-muted mb-0">
                Desde este panel podrás acceder
                a las herramientas administrativas
                de la biblioteca.
            </p>
        </div>
    </div>

</div>

@endsection