@extends('layouts.app')

@section('title', 'Enquetes e Votações')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    @hasanyrole('admin|sindico')
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center mb-4 text-indigo-700">
            <i class="fa-solid fa-square-plus text-xl mr-2"></i>
            <h3 class="text-lg font-bold">Criar Nova Enquete</h3>
        </div>
        
        <form action="{{ route('enquetes.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Título da Enquete *</label>
                    <input type="text" name="titulo" required placeholder="Ex: Qual será a cor da nova pintura da fachada?" class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 border">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Descrição / Detalhes</label>
                    <textarea name="descricao" rows="2" class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 border"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Data de Abertura *</label>
                    <input type="datetime-local" name="data_abertura" required class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Data de Encerramento *</label>
                    <input type="datetime-local" name="data_encerramento" required class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 border">
                </div>
            </div>

            <div class="p-4 bg-slate-50 border border-slate-100 rounded-lg mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Opções de Voto (Preencha pelo menos duas)</label>
                <div class="space-y-2">
                    <input type="text" name="opcoes[]" placeholder="Opção 1 (Ex: Azul Marinho)" required class="w-full border-slate-200 rounded-lg p-2 border text-sm">
                    <input type="text" name="opcoes[]" placeholder="Opção 2 (Ex: Verde Musgo)" required class="w-full border-slate-200 rounded-lg p-2 border text-sm">
                    <input type="text" name="opcoes[]" placeholder="Opção 3 (Opcional)" class="w-full border-slate-200 rounded-lg p-2 border text-sm">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
                    Publicar Enquete
                </button>
            </div>
        </form>
    </div>
    @endhasanyrole

    <h3 class="text-xl font-bold text-slate-800 border-b pb-2">Enquetes do Condomínio</h3>
    
    <div class="space-y-6">
        @forelse($enquetes as $enquete)
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 relative overflow-hidden">
            
            @php
                $isAberta = now() >= $enquete->data_abertura && now() <= $enquete->data_encerramento && $enquete->status;
            @endphp
            
            <div class="absolute top-0 right-0 px-4 py-1 text-xs font-bold text-white {{ $isAberta ? 'bg-green-500' : 'bg-slate-400' }} rounded-bl-lg">
                {{ $isAberta ? 'ABERTA PARA VOTAÇÃO' : 'ENCERRADA' }}
            </div>

            <h4 class="text-xl font-bold text-slate-800 mb-2 pr-32">{{ $enquete->titulo }}</h4>
            <p class="text-slate-600 mb-4 text-sm">{{ $enquete->descricao }}</p>
            
            <div class="flex items-center text-xs text-slate-500 mb-6 gap-4">
                <span><i class="fa-regular fa-clock mr-1"></i> Início: {{ \Carbon\Carbon::parse($enquete->data_abertura)->format('d/m/Y H:i') }}</span>
                <span><i class="fa-solid fa-flag-checkered mr-1"></i> Fim: {{ \Carbon\Carbon::parse($enquete->data_encerramento)->format('d/m/Y H:i') }}</span>
            </div>

            @php
                $totalVotos = $enquete->opcoes->sum(function($opcao) {
                    return $opcao->votos->count();
                });

                $jaVotou = $enquete->opcoes->contains(function($opcao) {
                    return $opcao->votos->where('user_id', Auth::id())->count() > 0;
                });

                $mostrarResultados = $jaVotou || !$isAberta;
            @endphp

            @if($mostrarResultados)
                <div class="bg-slate-50 p-5 rounded-lg border border-slate-100 space-y-5">
                    <p class="text-sm font-bold text-slate-500 mb-2 border-b pb-2">Resultados Parciais ({{ $totalVotos }} votos registrados)</p>
                    
                    @foreach($enquete->opcoes as $opcao)
                        @php
                            $votosOpcao = $opcao->votos->count();
                            $porcentagem = $totalVotos > 0 ? round(($votosOpcao / $totalVotos) * 100) : 0;
                            $meuVoto = $opcao->votos->where('user_id', Auth::id())->count() > 0;
                        @endphp
                        
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-bold {{ $meuVoto ? 'text-indigo-700' : 'text-slate-700' }}">
                                    {{ $opcao->texto }}
                                    @if($meuVoto) 
                                        <span class="text-[10px] bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full ml-2 uppercase font-black">Meu Voto</span> 
                                    @endif
                                </span>
                                <span class="text-slate-600 font-bold">{{ $porcentagem }}% <span class="text-xs font-normal text-slate-400">({{ $votosOpcao }})</span></span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2.5 shadow-inner">
                                <div class="bg-indigo-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $porcentagem }}%"></div>
                            </div>
                        </div>
                    @endforeach

                    @if(!$isAberta)
                        <div class="text-center text-sm font-bold text-slate-500 mt-4 pt-4 border-t border-slate-200">
                            <i class="fa-solid fa-lock mr-1"></i> Esta enquete já foi encerrada.
                        </div>
                    @else
                        <div class="text-center text-sm font-bold text-green-600 mt-4 pt-4 border-t border-green-100">
                            <i class="fa-solid fa-circle-check mr-1"></i> Seu voto foi registrado. Aguarde o encerramento.
                        </div>
                    @endif
                </div>

            @else
                <form action="{{ route('enquetes.votar', $enquete->id) }}" method="POST" class="bg-slate-50 p-5 rounded-lg border border-slate-100">
                    @csrf
                    <div class="space-y-3 mb-5">
                        <p class="text-sm font-bold text-slate-500 mb-2 border-b pb-2">Escolha uma opção para votar:</p>
                        @foreach($enquete->opcoes as $opcao)
                        <label class="flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-white transition-colors bg-white shadow-sm">
                            <input type="radio" name="enquete_opcao_id" value="{{ $opcao->id }}" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" required>
                            <span class="ml-3 text-slate-700 font-medium">{{ $opcao->texto }}</span>
                        </label>
                        @endforeach
                    </div>
                    
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-bold transition-colors shadow-sm">
                        Confirmar Meu Voto
                    </button>
                </form>
            @endif

        </div>
        @empty
        <div class="bg-white p-8 rounded-xl text-center shadow-sm border border-slate-100 text-slate-500">
            <i class="fa-solid fa-check-to-slot text-4xl mb-3 text-slate-300"></i>
            <p>Nenhuma enquete cadastrada no momento.</p>
        </div>
        @endforelse
        
        <div class="mt-4">
            {{ $enquetes->links() }}
        </div>
    </div>

</div>
@endsection