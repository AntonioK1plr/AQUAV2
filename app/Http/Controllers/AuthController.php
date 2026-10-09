<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditoriaService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function loginForm() { return Inertia::render('Auth/Login'); }

    public function login(Request $request, AuditoriaService $audit)
    {
        $data = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (!Auth::attempt($data + ['is_active' => true], $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => 'Credenciales incorrectas o cuenta desactivada.']);
        }

        $request->session()->regenerate();
        $audit->registrar($request, 'auth.login');

        if (mb_strtolower(Auth::user()->role) === 'cliente') {
            return redirect()->intended(route('catalogo.index'));
        }

        return redirect()->route(match (mb_strtolower(Auth::user()->role)) {
            'cajero' => 'pos.index',
            'almacenista' => 'almacen.picking',
            'veterinario' => 'vet.agenda',
            'administrador' => 'admin.dashboard',
            default => 'catalogo.index',
        });
    }

    public function registerForm() { return Inertia::render('Auth/Register'); }

    public function register(Request $request, AuditoriaService $audit)
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'email' => 'required|email|max:255|unique:users', 'password' => 'required|string|min:8|confirmed', 'telefono' => 'nullable|string|max:32']);
        $user = User::create($data + ['role' => 'Cliente', 'is_active' => true]);
        Auth::login($user);
        $request->session()->regenerate();
        $audit->registrar($request, 'auth.register');

        return redirect()->intended(route('catalogo.index'));
    }

    public function forgotForm() { return Inertia::render('Auth/ForgotPassword'); }
    public function sendResetLink(Request $request) { $request->validate(['email' => 'required|email']); $status = Password::sendResetLink($request->only('email')); return $status === Password::RESET_LINK_SENT ? back()->with('success', 'Si el correo está registrado, enviamos un enlace para restablecer la contraseña.') : back()->withErrors(['email' => __($status)]); }
    public function resetForm(Request $request, string $token) { return Inertia::render('Auth/ResetPassword', ['email' => $request->query('email'), 'token' => $token]); }

    public function reset(Request $request)
    {
        $request->validate(['token' => 'required', 'email' => 'required|email', 'password' => 'required|string|min:8|confirmed']);
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET ? redirect()->route('login')->with('success', 'Tu contraseña se actualizó. Ya puedes iniciar sesión.') : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request) { Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('home'); }
}
