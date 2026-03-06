<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // ─── Login ───────────────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Return view directly - NO SESSION, NO REDIRECT
        return response()->make(
            view('auth.login', [
                '__username_error' => 'Username atau Password Salah.',
                '__old_username' => $request->input('username'),
            ]),
            422
        );
    }

    // ─── Register ─────────────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validation - akan auto redirect back dengan errors jika gagal
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'alpha_dash'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_ids' => ['required', 'array', 'min:1', 'max:2'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign multiple roles
            $roles = Role::whereIn('id', $validated['role_ids'])->get();
            $user->syncRoles($roles);

            $roleNames = $user->getRoleNames()->implode(', ');
            return redirect()->route('users.index')
                ->with('success', "User '{$user->name}' berhasil dibuat dengan role '{$roleNames}'.");

        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());

            return redirect()->route('users.index')
                ->with('error', 'Gagal membuat user: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
