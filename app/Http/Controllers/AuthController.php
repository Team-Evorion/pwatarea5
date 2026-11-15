<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'email'    => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $readerRole = Role::where('name', 'Reader')->first();

        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $readerRole ? $readerRole->id : 3,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('books.index')->with('success', 'Cuenta creada con éxito.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
    $request->session()->regenerate();

    // Obtener el rol del usuario autenticado
    $role = Auth::user()->role?->name;

    // Seleccionar el dashboard según el rol
    $dashboard = match ($role) {
        'Administrator' => 'dashboard.administrator',
        'Librarian'     => 'dashboard.librarian',
        'Reader'        => 'dashboard.reader',
        default         => null,
    };

    // Evitar el acceso de usuarios sin rol válido
    if ($dashboard === null) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => 'Tu cuenta no tiene un rol válido.',
        ]);
    }

    // Redirigir al panel correspondiente
    return redirect()->route($dashboard)
        ->with('success', 'Bienvenido de nuevo.');
}

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}