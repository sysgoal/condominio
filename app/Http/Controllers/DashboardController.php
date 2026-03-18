<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Interacao;
use App\Models\Enquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Busca os 3 últimos avisos ou discussões do mural
        $ultimasInteracoes = Interacao::with('autor')
            ->where('status', true)
            ->latest()
            ->take(3)
            ->get();

        // 2. Busca apenas as enquetes que estão ABERTAS no momento
        $enquetesAtivas = Enquete::with('opcoes.votos')
            ->where('status', true)
            ->where('data_abertura', '<=', now())
            ->where('data_encerramento', '>=', now())
            ->latest()
            ->take(2)
            ->get();

        // 3. Calcula o Saldo em Caixa (Apenas se o usuário for Síndico ou Admin)
        $saldoEmCaixa = 0;
        if (Auth::user()->hasAnyRole(['admin', 'sindico'])) {
            $totalReceitas = Conta::where('tipo', 'receita')->sum('valor');
            $totalDespesas = Conta::where('tipo', 'despesa')->sum('valor');
            $saldoEmCaixa = $totalReceitas - $totalDespesas;
        }
        $moradoresInativos = User::role('morador')->where('is_active', false)->count();

        return view('dashboard', compact(
            'ultimasInteracoes', 
            'enquetesAtivas', 
            'saldoEmCaixa', 
            'moradoresInativos' // Passe para a view
        ));
        //return view('dashboard', compact('ultimasInteracoes', 'enquetesAtivas', 'saldoEmCaixa'));
    }
}