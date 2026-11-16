@extends('layouts.app')
@section('title', 'Crear cuenta · Biblioteca PWA')
@section('content')
<div class="auth-layout row g-0 mx-auto">
    <section class="col-lg-5 auth-promo d-none d-lg-flex" aria-label="Bienvenida">
        <div>
            <span class="hero-pill"><i class="bi bi-stars" aria-hidden="true"></i> TU RINCÓN DE LECTURA</span>
            <h1 class="mt-4">Las mejores historias empiezan aquí.</h1>
            <p>Únete a nuestra comunidad lectora y consulta tu biblioteca desde cualquier dispositivo.</p>
        </div>
        <div class="auth-promo-decoration" aria-hidden="true"><i class="bi bi-book-half"></i></div>
        <span class="auth-promo-footnote">BIBLIOTECA PWA · GESTIÓN DIGITAL</span>
    </section>
    <section class="col-lg-7 auth-panel">
        <span class="auth-kicker">CREA TU CUENTA DE LECTOR</span>
        <h2 class="auth-heading">Únete a la biblioteca</h2>
        <p class="auth-subheading">Completa tus datos para comenzar a explorar nuestra colección.</p>
        <form method="POST" action="{{ route('register') }}" class="mt-4">
            @csrf
            <div class="mb-3"><label class="form-label" for="username">Nombre de usuario</label><input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" autocomplete="username" required autofocus>@error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="email">Correo electrónico</label><input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="form-label" for="password">Contraseña</label><input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label" for="password_confirmation">Confirmar contraseña</label><input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password" required></div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-3"><i class="bi bi-person-plus me-2" aria-hidden="true"></i>Crear mi cuenta</button>
        </form>
        <p class="auth-footnote">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </section>
</div>
@endsection
