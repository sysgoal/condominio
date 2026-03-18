@extends('layouts.app')

@section('title', 'Adicionar Novo Morador')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('moradores.index') }}" class="text-slate-500 hover:text-indigo-600 mr-4 transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Dados do Novo Morador</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('moradores.store') }}" method="POST">
            @csrf
            <form action="{{ route('moradores.store') }}" method="POST">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                        <div class="flex items-center mb-2">
                            <i class="fa-solid fa-triangle-exclamation text-red-500 mr-2"></i>
                            <h3 class="text-sm font-bold text-red-800">Ops! Verifique os erros abaixo:</h3>
                        </div>
                        <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 border-b pb-2">Acesso ao Sistema</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nome Completo *</label>
                    <input type="text" name="name" required class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">E-mail *</label>
                    <input type="email" name="email" required class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Senha Inicial *</label>
                    <input type="password" name="password" required class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                </div>
            </div>

            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 border-b pb-2">Dados do Condomínio</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Bloco/Torre</label>
                    <input type="text" name="bloco" placeholder="Ex: A" class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Apartamento *</label>
                    <input type="text" name="apartamento" required placeholder="Ex: 104" class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Telefone (Opcional)</label>
                    <input type="text" name="telefone" placeholder="(11) 99999-9999" class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('moradores.index') }}" class="px-6 py-3 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors shadow-sm">
                    Cadastrar Morador
                </button>
            </div>
        </form>
    </div>
</div>
@endsection