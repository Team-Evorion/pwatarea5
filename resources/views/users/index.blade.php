@extends('layouts.app')
@section('title', 'Usuarios · Biblioteca PWA')
@section('content')
<div class="data-page-intro mt-3 mb-4"><div><span class="page-kicker"><i class="bi bi-people me-1" aria-hidden="true"></i> CONTROL DE ACCESO</span><h1 class="page-title">Usuarios y roles</h1><p class="page-description">Administra las cuentas registradas y sus permisos en Biblioteca PWA.</p></div><span class="results-pill"><i class="bi bi-person-check me-2" aria-hidden="true"></i>{{ $users->total() }} cuentas</span></div>
@if($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="GET" action="{{ route('users.index') }}" class="catalog-search mb-4" role="search"><div class="catalog-search-icon"><i class="bi bi-search" aria-hidden="true"></i></div><label class="visually-hidden" for="search">Buscar usuarios</label><input type="search" name="search" id="search" class="catalog-search-input" value="{{ $search }}" placeholder="Buscar nombre de usuario o correo..."><button class="btn btn-primary" type="submit">Buscar</button>@if($search)<a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Limpiar</a>@endif</form>
<div class="content-panel"><div class="content-panel-header"><div><h2>Directorio de usuarios</h2><p>Cambia los roles solo cuando sea necesario.</p></div></div><div class="table-responsive"><table class="table data-table align-middle mb-0"><thead><tr><th>CUENTA</th><th>CORREO</th><th>ROL</th><th>PRÉSTAMOS ACTIVOS</th><th>ACCIONES</th></tr></thead><tbody>
@forelse($users as $user)
    <tr><td><div class="user-table-cell"><span class="user-table-avatar">{{ strtoupper(substr($user->username, 0, 1)) }}</span><span><strong>{{ $user->username }}</strong>@if((int)$user->id === (int)Auth::id())<small>Tu cuenta</small>@endif</span></div></td><td>{{ $user->email }}</td><td><span class="role-chip">{{ $user->role?->name ?? 'Sin rol' }}</span></td><td><span class="loan-count">{{ $user->active_loans_count }}</span></td>
    <td>
        @if((int)$user->id === (int)Auth::id())
            <span class="text-muted small">Protegido</span>
        @else
            <form method="POST" action="{{ route('users.role.update', $user) }}" class="d-flex align-items-center gap-2 flex-wrap">
                @csrf @method('PATCH')
                <label class="visually-hidden" for="role-{{ $user->id }}">Rol de {{ $user->username }}</label>
                <select id="role-{{ $user->id }}" name="role_id" class="form-select form-select-sm" style="max-width:160px" required>
                @foreach($roles as $role)<option value="{{ $role->id }}" @selected((int)$user->role_id === (int)$role->id)>{{ $role->name }}</option>@endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary">Guardar</button>
            </form>
        @endif
    </td></tr>
@empty
    <tr><td colspan="5" class="text-center py-5 text-muted">No se encontraron usuarios.</td></tr>
@endforelse
</tbody></table></div></div>
<div class="mt-4">{{ $users->links('pagination::bootstrap-5') }}</div>
@endsection
