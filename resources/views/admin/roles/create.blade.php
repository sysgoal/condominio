@extends('layouts.app')

@section('title', 'Nova Função de Acesso')

@section('content')
<div class="max-w-3xl mx-auto">
    <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Cadastrar Novo Cargo</h2>
            
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Nome do Cargo/Função</label>
                <input type="text" name="name" placeholder="Ex: Zelador, Porteiro, Tesoureiro" required
                       class="w-full bg-slate-50 border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4">Permissões deste Cargo</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($permissoes as $permissao)
                <label class="flex items-center p-3 rounded-xl border border-slate-50 hover:bg-indigo-50 cursor-pointer transition-all group">
                    <input type="checkbox" name="permissions[]" value="{{ $permissao->name }}" 
                           class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <span class="ml-3 text-sm font-medium text-slate-600 group-hover:text-indigo-700">
                        {{ ucfirst(str_replace('-', ' ', $permissao->name)) }}
                    </span>
                </label>
                @endforeach
            </div>

            <div class="mt-10 pt-6 border-t border-slate-50 flex justify-end gap-3">
                <a href="{{ route('roles.index') }}" class="px-6 py-3 text-slate-500 font-bold">Cancelar</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg transition-all">
                    Criar Função
                </button>
            </div>
        </div>
    </form>
</div>
@endsection