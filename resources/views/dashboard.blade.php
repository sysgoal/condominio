@extends('layouts.app')

@section('title', 'Visão Geral do Condomínio')

@section('content')
<div class="space-y-6">

    <div class="bg-indigo-600 rounded-xl shadow-sm text-white p-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold mb-1">Olá, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-indigo-100 text-sm">Bem-vindo(a) ao painel digital do seu condomínio. Aqui está o resumo de hoje.</p>
        </div>
        <div class="hidden md:block text-5xl text-indigo-400 opacity-50">
            <i class="fa-solid fa-city"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            
            @hasanyrole('admin|sindico')
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-lg mr-4">
                        <i class="fa-solid fa-piggy-bank text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium">Saldo Atual em Caixa</p>
                        <p class="text-2xl font-bold {{ $saldoEmCaixa >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($saldoEmCaixa, 2, ',', '.') }}
                        </p>
                    </div>
                    @hasanyrole('admin|sindico')
    @if($moradoresInativos > 0)
    <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl mt-4 flex items-center justify-between">
        <div class="flex items-center text-amber-700">
            <i class="fa-solid fa-user-lock mr-3 text-lg"></i>
            <span class="text-sm font-medium">Existem <strong>{{ $moradoresInativos }}</strong> moradores com acesso desativado.</span>
        </div>
        <a href="{{ route('moradores.index') }}" class="text-xs font-bold text-amber-800 underline uppercase tracking-tighter">Verificar</a>
    </div>
    @endif
@endhasanyrole
                </div>
                <a href="{{ route('contas.index') }}" class="text-sm text-indigo-600 font-bold hover:underline">Ver detalhes &rarr;</a>
            </div>
            @endhasanyrole

            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800"><i class="fa-regular fa-newspaper mr-2 text-slate-400"></i> Últimas do Mural</h3>
                    <a href="{{ route('interacoes.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Ver tudo</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($ultimasInteracoes as $post)
                    <div class="p-6 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-slate-800 text-lg">{{ $post->titulo }}</h4>
                            <span class="px-2 py-1 text-[10px] font-bold rounded-full uppercase
                                {{ $post->tipo == 'venda' ? 'bg-purple-100 text-purple-700' : ($post->tipo == 'aviso' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $post->tipo }}
                            </span>
                        </div>
                        <p class="text-slate-600 text-sm line-clamp-2 mb-3">{{ $post->conteudo }}</p>
                        <div class="text-xs text-slate-400 font-medium flex items-center">
                            <i class="fa-solid fa-user-pen mr-1"></i> {{ $post->autor->name ?? 'Morador' }} &bull; {{ $post->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500">
                        Nenhuma novidade no mural no momento.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="space-y-6">
            <div class="bg-gradient-to-br from-indigo-800 to-indigo-900 rounded-xl shadow-sm border border-indigo-700 overflow-hidden text-white">
                <div class="px-6 py-4 border-b border-indigo-700/50 flex justify-between items-center">
                    <h3 class="font-bold"><i class="fa-solid fa-check-to-slot mr-2 text-indigo-300"></i> Enquetes Ativas</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    @forelse($enquetesAtivas as $enquete)
                        @php
                            $jaVotou = $enquete->opcoes->contains(function($opcao) {
                                return $opcao->votos->where('user_id', Auth::id())->count() > 0;
                            });
                        @endphp
                        
                        <div class="bg-white/10 p-4 rounded-lg border border-white/10">
                            <h4 class="font-bold text-lg mb-1 leading-tight">{{ $enquete->titulo }}</h4>
                            <p class="text-indigo-200 text-xs mb-3 flex items-center">
                                <i class="fa-regular fa-clock mr-1"></i> Encerra em {{ \Carbon\Carbon::parse($enquete->data_encerramento)->format('d/m') }}
                            </p>
                            
                            @if($jaVotou)
                                <div class="bg-green-500/20 text-green-300 text-sm font-bold p-2 rounded text-center border border-green-500/30">
                                    <i class="fa-solid fa-check mr-1"></i> Voto Registrado
                                </div>
                            @else
                                <a href="{{ route('enquetes.index') }}" class="block w-full bg-white text-indigo-900 text-center py-2 rounded font-bold text-sm hover:bg-indigo-50 transition-colors shadow-sm">
                                    Votar Agora
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-indigo-300 py-4 text-sm">
                            <i class="fa-solid fa-mug-hot mb-2 text-2xl opacity-50 block"></i>
                            Nenhuma votação ocorrendo agora.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection