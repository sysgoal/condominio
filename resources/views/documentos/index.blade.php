@extends('layouts.app')

@section('title', 'Documentos e Atas')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        
        <form action="{{ route('documentos.index') }}" method="GET" class="flex-1 w-full flex flex-col md:flex-row items-center gap-3">
            
            <div class="relative w-full md:w-auto flex-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Buscar por título ou conteúdo..." class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5 transition-colors">
            </div>

            <select name="tipo" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full md:w-48 p-2.5 transition-colors cursor-pointer">
                <option value="">Todos os Tipos</option>
                <option value="ata" {{ request('tipo') == 'ata' ? 'selected' : '' }}>Atas</option>
                <option value="regimento" {{ request('tipo') == 'regimento' ? 'selected' : '' }}>Regimento Interno</option>
                <option value="convencao" {{ request('tipo') == 'convencao' ? 'selected' : '' }}>Convenção</option>
            </select>

            <button type="submit" class="w-full md:w-auto bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold rounded-lg text-sm px-5 py-2.5 transition-colors shadow-sm">
                Filtrar
            </button>
            
            @if(request('busca') || request('tipo'))
                <a href="{{ route('documentos.index') }}" class="w-full md:w-auto text-center text-slate-400 hover:text-red-500 text-sm font-medium transition-colors" title="Limpar busca">
                    <i class="fa-solid fa-xmark text-lg md:ml-2"></i> Limpar
                </a>
            @endif
        </form>

        @hasanyrole('admin|sindico')
        <div class="w-full lg:w-auto flex justify-end">
            <a href="{{ route('documentos.create') }}" class="inline-flex items-center w-full justify-center lg:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-lg transition-colors shadow-sm">
                <i class="fa-solid fa-file-arrow-up mr-2"></i> Novo Documento
            </a>
        </div>
        @endhasanyrole
    </div>

    @if(request('busca') || request('tipo'))
        <div class="text-sm text-slate-500 font-medium px-2">
            Mostrando resultados 
            @if(request('busca')) para a busca: <span class="text-indigo-600 font-bold">"{{ request('busca') }}"</span> @endif
            @if(request('busca') && request('tipo')) e @endif
            @if(request('tipo')) do tipo: <span class="text-indigo-600 font-bold uppercase">{{ request('tipo') }}</span> @endif
        </div>
    @endif

    <div class="space-y-4">
        @forelse($documentos as $documento)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-6 items-start md:items-center hover:border-indigo-100 transition-colors">
                
                <div class="hidden md:flex flex-shrink-0 w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 items-center justify-center text-slate-400 text-3xl">
                    @if($documento->tipo == 'ata')
                        <i class="fa-solid fa-file-signature text-blue-500"></i>
                    @elseif($documento->tipo == 'regimento')
                        <i class="fa-solid fa-scale-balanced text-amber-500"></i>
                    @elseif($documento->tipo == 'convencao')
                        <i class="fa-solid fa-book text-emerald-500"></i>
                    @else
                        <i class="fa-solid fa-file-lines text-slate-400"></i>
                    @endif
                </div>

                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                            {{ $documento->tipo }}
                        </span>
                        <span class="text-xs font-medium text-slate-400">
                            <i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($documento->data_documento)->format('d/m/Y') }}
                        </span>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-2">{{ $documento->titulo }}</h4>
                    
                    @if($documento->conteudo_texto)
                        <p class="text-sm text-slate-600 line-clamp-2">{{ $documento->conteudo_texto }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    @if($documento->arquivo_path)
                        <a href="{{ asset('storage/' . $documento->arquivo_path) }}" target="_blank" class="flex-1 md:flex-none text-center bg-slate-50 hover:bg-indigo-50 text-indigo-600 border border-slate-200 hover:border-indigo-200 font-bold py-2 px-6 rounded-lg transition-colors whitespace-nowrap">
                            <i class="fa-solid fa-download mr-2"></i> Baixar
                        </a>
                    @else
                        <span class="flex-1 md:flex-none text-center bg-slate-50 text-slate-400 border border-slate-100 font-medium py-2 px-6 rounded-lg text-sm cursor-not-allowed">
                            Apenas Texto
                        </span>
                    @endif

                    @hasanyrole('admin|sindico')
                    <form action="{{ route('documentos.destroy', $documento->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar este documento permanentemente?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-slate-300 hover:text-red-500 transition-colors" title="Excluir Documento">
                            <i class="fa-solid fa-trash-can text-xl"></i>
                        </button>
                    </form>
                    @endhasanyrole
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-xl border border-slate-100 text-center shadow-sm">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-1">Nenhum documento encontrado</h3>
                <p class="text-slate-500 text-sm">Não encontramos nenhum resultado para o filtro selecionado.</p>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $documentos->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection