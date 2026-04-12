<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ContaController extends Controller
{
    // Mostra a prestação de contas e o saldo em caixa
    public function index()
    {
        $contas = Conta::orderBy('data_vencimento', 'desc')->paginate(15);
        
        $totalReceitas = Conta::where('tipo', 'receita')->sum('valor');
        $totalDespesas = Conta::where('tipo', 'despesa')->sum('valor');
        $saldoEmCaixa = $totalReceitas - $totalDespesas;

        return view('contas.index', compact('contas', 'totalReceitas', 'totalDespesas', 'saldoEmCaixa'));
    }

    public function create()
    {
        return view('contas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:receita,despesa',
            'categoria' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date',
            'data_pagamento' => 'nullable|date',
            'comprovante' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        // Upload do comprovante, se houver
        if ($request->hasFile('comprovante')) {
            $path = $request->file('comprovante')->store('comprovantes', 'public');
            $validated['comprovante_path'] = $path;
        }

        Conta::create($validated);

        return redirect()->route('contas.index')->with('success', 'Registro financeiro adicionado com sucesso!');
    }

    public function relatorio(Request $request)
    {
        // 1. Filtros de Mês e Ano (padrão atual)
        $mes = $request->get('mes', Carbon::now()->month);
        $ano = $request->get('ano', Carbon::now()->year);
    
        // 2. Busca as contas filtradas
        $contas = \App\Models\Conta::whereMonth('data_vencimento', $mes)
        ->whereYear('data_vencimento', $ano)
        ->get();
        // 3. Cálculos dos Totais
        $totalReceitas = $contas->where('tipo', 'receita')->sum('valor');
        $totalDespesas = $contas->where('tipo', 'despesa')->sum('valor');
        $saldoMes = $totalReceitas - $totalDespesas;
    
        // 4. PREPARAÇÃO DOS DADOS PARA O GRÁFICO (Despesas por Categoria)
        // Agrupa as despesas por categoria e soma os valores
        $despesasPorCategoria = $contas->where('tipo', 'despesa')
                                   ->groupBy('categoria')
                                   ->map(fn($row) => $row->sum('valor'));
    
        // Separa os nomes das categorias (Labels) e os valores (Data) em arrays simples
        $graficoLabels = $despesasPorCategoria->keys()->all() ?: [];
        $graficoValores = $despesasPorCategoria->values()->all() ?: [];
    
        return view('contas.relatorio', compact(
            'contas', 'totalReceitas', 'totalDespesas', 'saldoMes', 'mes', 'ano',
            'graficoLabels', 'graficoValores'
        ));
    }

    public function destroy($id)
{
    // 1. Verificação redundante de segurança
    if (!auth()->user()->hasAnyRole(['admin', 'sindico'])) {
        abort(403, 'Ação não autorizada.');
    }

    $registro = Conta::findOrFail($id);
    
    // 2. Executa a exclusão
    $registro->delete();

    // 3. Retorno com mensagem (que aparecerá no seu layout novo)
    return redirect()->back()->with('success', 'Registro excluído com sucesso!');
}
}