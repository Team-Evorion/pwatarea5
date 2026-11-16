@extends('layouts.app')
@section('title', 'Iniciar sesión · Biblioteca PWA')
@section('content')
<div class="auth-layout row g-0 mx-auto">
    <section class="col-lg-5 auth-promo d-none d-lg-flex" aria-label="Bienvenida">
        <div>
            <span class="hero-pill"><i class="bi bi-stars" aria-hidden="true"></i> TU BIBLIOTECA DIGITAL</span>
            <h1 class="mt-4">Cada libro abre una nueva puerta.</h1>
            <p>Gestiona tus préstamos, descubre obras y continúa leyendo desde un solo lugar.</p>
        </div>
        <div class="auth-promo-decoration" aria-hidden="true"><i class="bi bi-book-half"></i></div>
        <span class="auth-promo-footnote">BIBLIOTECA PWA · GESTIÓN DIGITAL</span>
    </section>
    <section class="col-lg-7 auth-panel">
        <span class="auth-kicker">BIENVENIDO DE NUEVO</span>
        <h2 class="auth-heading">Inicia sesión</h2>
        <p class="auth-subheading">Accede a tu espacio y mantén tus lecturas organizadas.</p>
        <form method="POST" action="{{ route('login') }}" class="mt-4">
            @csrf
            <div class="mb-3"><label class="form-label" for="email">Correo electrónico</label><input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="tu@correo.com" autocomplete="username" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-4"><label class="form-label" for="password">Contraseña</label><input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <button type="submit" class="btn btn-primary w-100 py-3"><i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Ingresar a Biblioteca PWA</button>
        </form>
        <p class="auth-footnote">¿Aún no tienes una cuenta? <a href="{{ route('register') }}">Regístrate como lector</a></p>
    </section>
</div>
@endsection
