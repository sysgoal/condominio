@extends('layouts.app')

@section('title', 'Editar Morador')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('moradores.index') }}" class="text-slate-500 hover:text-indigo-600 mr-4 transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Editar Dados: {{ $user->name }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('moradores.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 border-b pb-2 text-indigo-600">Acesso ao Sistema</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nome Completo</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full border-slate-200 rounded-lg p-3 border">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">E-mail</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full border-slate-200 rounded-lg p-3 border">
                </div>
            </div>

            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 border-b pb-2 text-indigo-600">Dados do Condomínio</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Bloco</label>
                    <input type="text" name="bloco" value="{{ $user->morador->bloco }}" class="w-full border-slate-200 rounded-lg p-3 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Apto</label>
                    <input type="text" name="apartamento" value="{{ $user->morador->apartamento }}" required class="w-full border-slate-200 rounded-lg p-3 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Telefone</label>
                    <input type="text" name="telefone" value="{{ $user->morador->telefone }}" class="w-full border-slate-200 rounded-lg p-3 border">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('moradores.index') }}" class="px-6 py-3 text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-medium transition-colors">Cancelar</a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 shadow-sm transition-all">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection