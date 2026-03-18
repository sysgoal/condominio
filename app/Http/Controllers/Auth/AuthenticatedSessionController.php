<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(\App\Http\Requests\Auth\LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        // 1. Tenta autenticar (valida e-mail e senha)
        $request->authenticate();

        // 2. NOVA VERIFICAÇÃO: O usuário está ativo?
        if (!auth()->user()->is_active) {
            // Se estiver inativo, desloga ele imediatamente
            auth()->guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Devolve para o login com uma mensagem de erro
            return redirect()->route('login')->withErrors([
                'email' => 'Sua conta está desativada. Por favor, entre em contato com a administração.',
            ]);
        }

        // 3. Se estiver tudo certo e ativo, segue o fluxo normal
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
