<?php

namespace App\Http\Controllers;

use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    public function store(Request $request, LoanService $loanService): RedirectResponse
    {
        $usuario = $request->user();
        $rol = DB::table('roles')->where('id', $usuario->role_id)->value('name');
        abort_unless(in_array($rol, ['Reader', 'Librarian'], true), 403);

        $datos = $request->validate([
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ($rol === 'Reader') {
            $usuarioId = (int) $usuario->id;
        } else {
            $usuarioId = (int) ($datos['user_id'] ?? 0);
            if ($usuarioId === 0) {
                throw ValidationException::withMessages([
                    'user_id' => 'Selecciona un lector.',
                ]);
            }

            $rolDestino = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('users.id', $usuarioId)
                ->value('roles.name');
            abort_unless($rolDestino === 'Reader', 403);
        }

        $loanService->prestarLibro($usuarioId, (int) $datos['book_id']);

        return back()->with('success', 'Préstamo registrado correctamente.');
    }

    public function update(Request $request, int $prestamoId, LoanService $loanService): RedirectResponse
    {
        $usuario = $request->user();
        $rol = DB::table('roles')->where('id', $usuario->role_id)->value('name');
        abort_unless(in_array($rol, ['Reader', 'Librarian'], true), 403);

        $loanService->devolverLibro(
            $prestamoId,
            (int) $usuario->id,
            $rol === 'Librarian'
        );

        return back()->with('success', 'Devolución registrada correctamente.');
    }
}
