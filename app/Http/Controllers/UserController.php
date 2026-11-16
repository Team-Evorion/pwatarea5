<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->with('role')
            ->withCount([
                'transactions as active_loans_count' =>
                    fn ($query) => $query->whereNull('date_of_return'),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $roles = Role::query()
            ->whereIn('name', ['Administrator', 'Librarian', 'Reader'])
            ->orderBy('id')
            ->get();

        return view('users.index', compact('users', 'roles', 'search'));
    }

    public function updateRole(Request $request, User $user)
    {
        $allowedRoleIds = Role::query()
            ->whereIn('name', ['Administrator', 'Librarian', 'Reader'])
            ->pluck('id')
            ->all();

        $validated = $request->validate([
            'role_id' => [
                'required',
                'integer',
                Rule::in($allowedRoleIds),
            ],
        ]);

        // Evitar que el administrador cambie su propio rol
        if ((int) $request->user()->id === (int) $user->id) {
            return back()->with(
                'error',
                'No puedes cambiar tu propio rol.'
            );
        }

        $newRole = Role::findOrFail((int) $validated['role_id']);

        // Conservar como lector a quien tenga libros pendientes
        if (
            $user->role?->name === 'Reader'
            && $newRole->name !== 'Reader'
            && $user->transactions()
                ->whereNull('date_of_return')
                ->exists()
        ) {
            return back()->with(
                'error',
                'No puedes cambiar el rol de un lector con préstamos activos.'
            );
        }

        if ((int) $user->role_id === (int) $newRole->id) {
            return back()->with(
                'success',
                'El usuario ya tenía ese rol.'
            );
        }

        $user->update([
            'role_id' => $newRole->id,
        ]);

        return back()->with(
            'success',
            "Rol de {$user->username} actualizado correctamente."
        );
    }
}
