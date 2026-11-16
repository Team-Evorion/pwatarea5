<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Biblioteca Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('books.index') }}">Biblioteca PWA</a>
            <button class="navbar-toggler" type="button"
        data-bs-toggle="collapse"
        data-bs-target="#menuBiblioteca"
        aria-controls="menuBiblioteca"
        aria-expanded="false"
        aria-label="Mostrar menú">
    <span class="navbar-toggler-icon"></span>
</button>
            <div class="collapse navbar-collapse" id="menuBiblioteca">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                    @php
    $dashboardRoute = match (Auth::user()->role?->name) {
        'Administrator' => 'dashboard.administrator',
        'Librarian'     => 'dashboard.librarian',
        'Reader'        => 'dashboard.reader',
        default         => null,
    };
@endphp

@if($dashboardRoute)
    <li class="nav-item">
        <a class="nav-link text-white"
           href="{{ route($dashboardRoute) }}">
            Mi Panel
        </a>
    </li>
@endif
   <li class="nav-item">
    <a class="nav-link text-white"
       href="{{ route('books.index') }}">
        Catálogo de libros
    </a>
</li>

    @if(in_array(Auth::user()->role?->name, ['Administrator', 'Librarian']))
        <li class="nav-item">
            <a class="nav-link text-white"
               href="{{ route('books.create') }}">
                Agregar libro
            </a>
        </li>
    @endif
@endauth
                    @auth
                        <li class="nav-item me-3 text-light">
                            Hola, <strong>{{ Auth::user()->username }}</strong> 
                            <span class="badge bg-secondary ms-1">{{ Auth::user()->role->name ?? 'Reader' }}</span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger" type="submit">Cerrar sesión</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>