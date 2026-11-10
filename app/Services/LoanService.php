<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanService
{
    public function prestarLibro(int $usuarioId, int $libroId): Transaction
    {
        return DB::transaction(function () use ($usuarioId, $libroId): Transaction {
            // Bloquear la fila del libro serializa los préstamos de este título.
            $libro = Book::query()->whereKey($libroId)->lockForUpdate()->firstOrFail();

            $prestados = Transaction::query()
                ->where('book_id', $libroId)
                ->whereNull('date_of_return')
                ->count();

            if ($prestados >= $libro->quantity) {
                throw ValidationException::withMessages([
                    'book_id' => 'No hay ejemplares disponibles.',
                ]);
            }

            $duplicado = Transaction::query()
                ->where('book_id', $libroId)
                ->where('user_id', $usuarioId)
                ->whereNull('date_of_return')
                ->exists();

            if ($duplicado) {
                throw ValidationException::withMessages([
                    'book_id' => 'Ya tienes un préstamo activo de este libro.',
                ]);
            }

            return Transaction::create([
                'user_id' => $usuarioId,
                'book_id' => $libroId,
                'date_of_issue' => now()->toDateString(),
                'date_of_return' => null,
            ]);
        }, 3);
    }

    public function devolverLibro(int $prestamoId, int $usuarioId, bool $esBibliotecario): Transaction
    {
        return DB::transaction(function () use ($prestamoId, $usuarioId, $esBibliotecario): Transaction {
            $prestamo = Transaction::query()
                ->whereKey($prestamoId)
                ->lockForUpdate()
                ->firstOrFail();

            // Validar propiedad dentro de la transacción, sobre el registro bloqueado.
            abort_unless(
                $esBibliotecario || (int) $prestamo->user_id === $usuarioId,
                403,
                'No puedes devolver préstamos de otros usuarios.'
            );

            if ($prestamo->date_of_return !== null) {
                throw ValidationException::withMessages([
                    'prestamo' => 'Este libro ya fue devuelto.',
                ]);
            }

            $prestamo->date_of_return = now()->toDateString();
            $prestamo->save();

            return $prestamo;
        }, 3);
    }
}
