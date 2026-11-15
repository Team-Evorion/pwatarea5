@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Préstamos de Biblioteca</h2>

    {{-- Botón para registrar préstamos --}}
    @if(in_array(auth()->user()->role?->name, ['Administrator', 'Librarian'], true))
        <div class="mb-3">
            <a href="{{ route('transactions.create') }}"
               class="btn btn-primary">
                + Registrar préstamo
            </a>
        </div>
    @endif





    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Libro</th>
                            <th>Lector</th>
                            <th>Fecha de préstamo</th>
                            <th>Fecha de devolución</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                {{-- Libro --}}
                                <td>
                                    {{ $transaction->book?->title ?? 'Libro no disponible' }}
                                </td>

                                {{-- Lector --}}
                                <td>
                                    {{ $transaction->user?->username ?? 'Usuario no disponible' }}
                                </td>

                                {{-- Fecha de préstamo --}}
                                <td>
                                    {{ $transaction->date_of_issue ?? 'Sin fecha' }}
                                </td>

                                {{-- Fecha de devolución --}}
                                <td>
                                    {{ $transaction->date_of_return ?? 'Pendiente' }}
                                </td>

                                {{-- Estado --}}
                                <td>
                                    @if($transaction->date_of_return)
                                        <span class="badge bg-success">
                                            Devuelto
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Prestado
                                        </span>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td>
                                    @if(in_array(auth()->user()->role?->name, ['Administrator', 'Librarian'], true))

                                        @if(is_null($transaction->date_of_return))
                                            <form
                                                action="{{ route('transactions.return', $transaction) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Confirmas la devolución de este libro?');"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="btn btn-success btn-sm">
                                                    Devolver libro
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-success">
                                                Devuelto
                                            </span>
                                        @endif

                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    No hay préstamos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>
@endsection