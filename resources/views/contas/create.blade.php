@extends('layouts.app')

@section('title', 'Novo Lançamento Financeiro')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('contas.index') }}" class="text-slate-500 hover:text-indigo-600 mr-4 transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Novo Lançamento</h2>
            <p class="text-slate-500 text-sm">Registre uma nova receita ou despesa no caixa do condomínio.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('contas.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tipo</label>
                    <select name="tipo" required class="w-full bg-slate-50 border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                        <option value="receita">💰 Receita (Entrada)</option>
                        <option value="despesa">💸 Despesa (Saída)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Categoria</label>
                    <input type="text" name="categoria" placeholder="Ex: Manutenção, Água, Taxa..." required
                           class="w-full bg-slate-50 border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Descrição Detalhada</label>
                    <input type="text" name="descricao" placeholder="Ex: Conserto do portão eletrônico bloco A" required
                           class="w-full bg-slate-50 border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Valor (R$)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-400 font-bold">R$</span>
                        <input type="number" name="valor" step="0.01" placeholder="0,00" required
                               class="w-full bg-slate-50 border-slate-200 rounded-xl p-3 pl-12 focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Data de Vencimento</label>
                    <input type="date" name="data_vencimento" required
                           class="w-full bg-slate-50 border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Data de Pagamento (Opcional)</label>
                    <input type="date" name="data_pagamento" 
                           class="w-full bg-slate-50 border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                    <p class="text-[10px] text-slate-400 mt-1 italic">Deixe vazio se a conta ainda não foi paga.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-50">
                <a href="{{ route('contas.index') }}" class="px-6 py-3 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-bold transition-all">
                    Cancelar
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-95">
                    Salvar Lançamento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection