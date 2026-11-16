@extends('layouts.app')
@section('title', 'Préstamos · Biblioteca PWA')
@section('content')
@php
    $canManageLoans = in_array(Auth::user()->role?->name, ['Administrator','Librarian'], true);
@endphp
<div class="data-page-intro mt-3 mb-4">
    <div><span class="page-kicker"><i class="bi bi-journal-bookmark me-1" aria-hidden="true"></i> GESTIÓN DE PRÉSTAMOS</span><h1 class="page-title">{{ $canManageLoans ? 'Préstamos y devoluciones' : 'Mis préstamos' }}</h1><p class="page-description">{{ $canManageLoans ? 'Supervisa los movimientos y el historial de la biblioteca.' : 'Consulta tus préstamos actuales y los libros que ya devolviste.' }}</p></div>
    @if($canManageLoans)<a class="btn btn-primary" href="{{ route('transactions.create') }}"><i class="bi bi-plus-circle me-2" aria-hidden="true"></i>Registrar préstamo</a>@endif
</div>

@if($errors->any())
<div class="alert alert-danger" role="alert"><strong>No se pudo completar la operación:</strong><ul class="mt-2 mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="content-panel">
    <div class="content-panel-header"><div><h2>Historial de movimientos</h2><p>Filtra los registros según su estado.</p></div><div class="filter-tabs" aria-label="Filtrar préstamos">
        <a href="{{ route('transactions.index', ['status'=>'all']) }}" class="filter-tab {{ $status === 'all' ? 'active' : '' }}">Todos</a>
        <a href="{{ route('transactions.index', ['status'=>'borrowed']) }}" class="filter-tab {{ $status === 'borrowed' ? 'active' : '' }}">Activos</a>
        <a href="{{ route('transactions.index', ['status'=>'returned']) }}" class="filter-tab {{ $status === 'returned' ? 'active' : '' }}">Devueltos</a>
    </div></div>
    <div class="table-responsive">
    <table class="table data-table align-middle mb-0">
        <thead><tr><th>LIBRO</th><th>LECTOR</th><th>FECHA DE PRÉSTAMO</th><th>DEVOLUCIÓN</th><th>ESTADO</th><th>ACCIONES</th></tr></thead>
        <tbody>
        @forelse($transactions as $transaction)
            <tr>
                <td class="fw-bold">{{ $transaction->book?->title ?? 'Libro no disponible' }}</td>
                <td>{{ $transaction->user?->username ?? 'Usuario no disponible' }}</td>
                <td>{{ $transaction->date_of_issue ?? 'Sin fecha' }}</td>
                <td>{{ $transaction->date_of_return ?? 'Pendiente' }}</td>
                <td>@if($transaction->date_of_return !== null)<span class="status-label status-done"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Devuelto</span>@else<span class="status-label status-active"><i class="bi bi-clock-fill" aria-hidden="true"></i> Prestado</span>@endif</td>
                <td>
                    @if($canManageLoans && $transaction->date_of_return === null)
                        <form action="{{ route('transactions.return', $transaction) }}" method="POST" onsubmit="return confirm('¿Confirmas la devolución de este libro?');">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-primary">Registrar devolución</button>
                        </form>
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-inbox d-block fs-2 mb-2" aria-hidden="true"></i>No hay préstamos registrados en este filtro.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $transactions->links('pagination::bootstrap-5') }}</div>
@endsection
