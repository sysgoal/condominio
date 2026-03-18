@extends('layouts.app')

@section('title', 'Gestão Financeira')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center">
        <div class="p-4 bg-green-50 text-green-600 rounded-lg mr-4">
            <i class="fa-solid fa-arrow-trend-up text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Total de Receitas</p>
            <p class="text-2xl font-bold text-slate-800">R$ {{ number_format($totalReceitas ?? 0, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center">
        <div class="p-4 bg-red-50 text-red-600 rounded-lg mr-4">
            <i class="fa-solid fa-arrow-trend-down text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Total de Despesas</p>
            <p class="text-2xl font-bold text-slate-800">R$ {{ number_format($totalDespesas ?? 0, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center">
        <div class="p-4 bg-indigo-50 text-indigo-600 rounded-lg mr-4">
            <i class="fa-solid fa-piggy-bank text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Saldo em Caixa</p>
            <p class="text-2xl font-bold {{ ($saldoEmCaixa ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                R$ {{ number_format($saldoEmCaixa ?? 0, 2, ',', '.') }}
            </p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h3 class="text-lg font-semibold text-slate-800">Últimos Lançamentos</h3>
       @hasanyrole('admin|sindico')
        <a href="{{ route('contas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fa-solid fa-plus mr-1"></i> Novo Lançamento
        </a>
       
                <a href="{{ route('contas.relatorio') }}" class="inline-flex items-center justify-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold py-2.5 px-6 rounded-lg transition-all shadow-sm">
                    <i class="fa-solid fa-file-invoice-dollar mr-2 text-indigo-600"></i> Relatório Mensal
                </a>
       
        @endhasanyrole
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Descrição</th>
                    <th class="px-6 py-4 font-medium">Categoria</th>
                    <th class="px-6 py-4 font-medium">Vencimento</th>
                    <th class="px-6 py-4 font-medium">Valor</th>
                    <th class="px-6 py-4 font-medium">Tipo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($contas as $conta)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-slate-800 font-medium">{{ $conta->descricao }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ ucfirst($conta->categoria) }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ \Carbon\Carbon::parse($conta->data_vencimento)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 font-bold {{ $conta->tipo == 'receita' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $conta->tipo == 'receita' ? '+' : '-' }} R$ {{ number_format($conta->valor, 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $conta->tipo == 'receita' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($conta->tipo) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">Nenhum lançamento encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection