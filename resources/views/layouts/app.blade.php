<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Biblioteca PWA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- El CSS personalizado se importa desde resources/js/app.js.
         No cargamos el preflight de Tailwind encima de Bootstrap. --}}
    @vite(['resources/js/app.js'])
</head>
<body class="biblioteca-body">
@php
    /** @var \App\Models\User|null $currentUser */
    $currentUser = \Illuminate\Support\Facades\Auth::user();
    $role = $currentUser?->role?->name;
    $dashboardRoute = match ($role) {
        'Administrator' => 'dashboard.administrator',
        'Librarian' => 'dashboard.librarian',
        'Reader' => 'dashboard.reader',
        default => 'login',
    };
    $roleLabel = match ($role) {
        'Administrator' => 'Administrador',
        'Librarian' => 'Bibliotecario',
        'Reader' => 'Lector',
        default => 'Invitado',
    };
    $canManageBooks = in_array($role, ['Administrator', 'Librarian'], true);
    $isAdministrator = $role === 'Administrator';
@endphp

@if($currentUser)
    <div class="library-shell">
        {{-- Navegación lateral para escritorio --}}
        <aside class="library-sidebar d-none d-lg-flex" aria-label="Menú principal">
            <a class="library-brand" href="{{ route($dashboardRoute) }}">
                <span class="brand-emblem"><i class="bi bi-book-half" aria-hidden="true"></i></span>
                <span><strong>Biblioteca<span class="brand-accent">.</span></strong><small>GESTIÓN DIGITAL</small></span>
            </a>

            <div class="sidebar-section-label">ESPACIO DE TRABAJO</div>
            <nav class="sidebar-nav">
                <a class="sidebar-link {{ request()->routeIs('dashboard.*') ? 'is-active' : '' }}" href="{{ route($dashboardRoute) }}">
                    <i class="bi bi-grid-1x2" aria-hidden="true"></i><span>Mi panel</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('books.index', 'books.edit') ? 'is-active' : '' }}" href="{{ route('books.index') }}">
                    <i class="bi bi-collection" aria-hidden="true"></i><span>Catálogo de libros</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('transactions.index') ? 'is-active' : '' }}" href="{{ route('transactions.index') }}">
                    <i class="bi bi-journal-bookmark" aria-hidden="true"></i><span>{{ $role === 'Reader' ? 'Mis préstamos' : 'Préstamos' }}</span>
                </a>
                @if($isAdministrator)
                    <a class="sidebar-link {{ request()->routeIs('users.*') ? 'is-active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-people" aria-hidden="true"></i><span>Usuarios y roles</span>
                    </a>
                @endif
            </nav>

            @if($canManageBooks)
                <div class="sidebar-section-label sidebar-section-spaced">ACCIONES RÁPIDAS</div>
                <nav class="sidebar-nav">
                    <a class="sidebar-link {{ request()->routeIs('books.create') ? 'is-active' : '' }}" href="{{ route('books.create') }}">
                        <i class="bi bi-plus-square" aria-hidden="true"></i><span>Agregar libro</span>
                    </a>
                    <a class="sidebar-link {{ request()->routeIs('transactions.create') ? 'is-active' : '' }}" href="{{ route('transactions.create') }}">
                        <i class="bi bi-arrow-left-right" aria-hidden="true"></i><span>Nuevo préstamo</span>
                    </a>
                </nav>
            @endif

            <div class="sidebar-bottom">
                <div class="sidebar-profile">
                    <span class="profile-avatar">{{ strtoupper(substr($currentUser->username, 0, 1)) }}</span>
                    <span class="profile-details"><strong>{{ $currentUser->username }}</strong><small>{{ $roleLabel }}</small></span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Cerrar sesión</button>
                </form>
            </div>
        </aside>

        <div class="library-workspace">
            <header class="library-topbar">
                <div class="topbar-left">
                    <button class="mobile-menu-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileLibraryMenu" aria-controls="mobileLibraryMenu" aria-label="Abrir menú">
                        <i class="bi bi-list" aria-hidden="true"></i>
                    </button>
                    <div>
                        <span class="topbar-eyebrow">BIBLIOTECA PWA / {{ $roleLabel }}</span>
                        <div class="topbar-title">Tu espacio de lectura</div>
                    </div>
                </div>
                <div class="topbar-right">
                    <span class="topbar-role d-none d-sm-inline-flex"><i class="bi bi-shield-check" aria-hidden="true"></i> {{ $roleLabel }}</span>
                    <span class="topbar-avatar" title="{{ $currentUser->username }}">{{ strtoupper(substr($currentUser->username, 0, 1)) }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-none d-md-block">
                        @csrf
                        <button type="submit" class="topbar-logout" title="Cerrar sesión"><i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Cerrar sesión</span></button>
                    </form>
                </div>
            </header>

            <main class="library-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif
                @yield('content')
            </main>
            <footer class="library-footer">Biblioteca PWA <span>·</span> Sistema de gestión bibliotecaria</footer>
        </div>
    </div>

    {{-- Menú móvil: independiente de la navegación de escritorio --}}
    <div class="offcanvas offcanvas-start library-mobile-panel" tabindex="-1" id="mobileLibraryMenu" aria-labelledby="mobileLibraryMenuLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title h5" id="mobileLibraryMenuLabel"><i class="bi bi-book-half me-2" aria-hidden="true"></i>Biblioteca.</h2>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar menú"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <span class="sidebar-section-label">NAVEGACIÓN</span>
            <nav class="sidebar-nav">
                <a class="sidebar-link {{ request()->routeIs('dashboard.*') ? 'is-active' : '' }}" href="{{ route($dashboardRoute) }}"><i class="bi bi-grid-1x2" aria-hidden="true"></i> Mi panel</a>
                <a class="sidebar-link {{ request()->routeIs('books.*') ? 'is-active' : '' }}" href="{{ route('books.index') }}"><i class="bi bi-collection" aria-hidden="true"></i> Catálogo de libros</a>
                <a class="sidebar-link {{ request()->routeIs('transactions.*') ? 'is-active' : '' }}" href="{{ route('transactions.index') }}"><i class="bi bi-journal-bookmark" aria-hidden="true"></i> {{ $role === 'Reader' ? 'Mis préstamos' : 'Préstamos' }}</a>
                @if($isAdministrator)
                    <a class="sidebar-link {{ request()->routeIs('users.*') ? 'is-active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-people" aria-hidden="true"></i> Usuarios y roles</a>
                @endif
                @if($canManageBooks)
                    <a class="sidebar-link" href="{{ route('books.create') }}"><i class="bi bi-plus-square" aria-hidden="true"></i> Agregar libro</a>
                    <a class="sidebar-link" href="{{ route('transactions.create') }}"><i class="bi bi-arrow-left-right" aria-hidden="true"></i> Nuevo préstamo</a>
                @endif
            </nav>
            <div class="mt-auto pt-4">
                <div class="sidebar-profile mb-3"><span class="profile-avatar">{{ strtoupper(substr($currentUser->username, 0, 1)) }}</span><span class="profile-details"><strong>{{ $currentUser->username }}</strong><small>{{ $roleLabel }}</small></span></div>
                <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="sidebar-logout"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Cerrar sesión</button></form>
            </div>
        </div>
    </div>
@else
    <div class="guest-shell">
        <header class="guest-header">
            <a class="guest-brand" href="{{ route('login') }}"><span class="brand-emblem"><i class="bi bi-book-half" aria-hidden="true"></i></span><strong>Biblioteca<span class="brand-accent">.</span></strong></a>
            <nav class="d-flex align-items-center gap-2" aria-label="Acceso">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Crear cuenta</a>
            </nav>
        </header>
        <main class="guest-main container">
            @if($errors->any())
                <div class="alert alert-danger" role="alert"><strong>Revisa los datos ingresados.</strong></div>
            @endif
            @yield('content')
        </main>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
