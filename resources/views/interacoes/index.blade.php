@extends('layouts.app')

@section('title', 'Mural do Condomínio')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex gap-4">
            <div class="hidden sm:flex flex-shrink-0 w-12 h-12 rounded-full bg-indigo-600 items-center justify-center text-white font-bold text-xl shadow-inner">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <form action="{{ route('interacoes.store') }}" method="POST" class="flex-1 space-y-4">
                @csrf
                <div>
                    <input type="text" name="titulo" placeholder="Título do seu aviso ou anúncio..." required
                           class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 font-bold focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                </div>
                <div>
                    <textarea name="conteudo" rows="3" placeholder="O que você gostaria de compartilhar com os vizinhos?" required
                              class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-600 text-sm focus:ring-2 focus:ring-indigo-500 transition-all outline-none resize-none"></textarea>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                    <div class="flex gap-2 w-full sm:w-auto">
                        <select name="tipo" class="bg-slate-50 border-none rounded-lg text-xs font-bold text-slate-500 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="discussao">📢 Discussão</option>
                            <option value="venda">💰 Classificado</option>
                            <option value="aviso">⚠️ Aviso</option>
                        </select>
                        <input type="number" name="preco" step="0.01" placeholder="Preço (opcional)" 
                               class="bg-slate-50 border-none rounded-lg text-xs w-28 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl transition-all shadow-md hover:shadow-indigo-200">
                        Publicar no Mural
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($interacoes as $postagem)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 pb-4 flex justify-between items-start">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold border border-slate-200">
                            {{ substr($postagem->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-slate-800">{{ $postagem->user->name }}</h4>
                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 tracking-wider">
                                    {{ $postagem->tipo }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400">
                                {{ $postagem->created_at->diffForHumans() }} 
                                @if($postagem->bloco) • Bloco {{ $postagem->bloco }} @endif
                            </p>
                        </div>
                    </div>

                    @can('delete', $postagem)
                        <form action="{{ route('interacoes.destroy', $postagem->id) }}" method="POST" onsubmit="return confirm('Excluir esta publicação permanentemente?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors p-2">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    @endcan
                </div>

                <div class="px-6 pb-4 space-y-3">
                    <h3 class="text-lg font-extrabold text-slate-800 leading-tight">{{ $postagem->titulo }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $postagem->conteudo }}</p>
                    
                    @if($postagem->preco)
                        <div class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg font-bold text-sm border border-emerald-100">
                            <i class="fa-solid fa-tag mr-2"></i> R$ {{ number_format($postagem->preco, 2, ',', '.') }}
                        </div>
                    @endif
                </div>

                <div class="px-6 py-3 bg-slate-50/50 border-y border-slate-100 flex items-center gap-6">
                    <form action="{{ route('interacoes.curtir', $postagem->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center text-slate-500 hover:text-indigo-600 font-bold text-sm transition-all group">
                            <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center mr-2 group-hover:bg-indigo-50 transition-colors">
                                <i class="fa-regular fa-thumbs-up"></i>
                            </div>
                            <span>{{ $postagem->curtidas ?? 0 }} Curtidas</span>
                        </button>
                    </form>

                    <div class="flex items-center text-slate-500 font-bold text-sm cursor-default">
                        <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center mr-2">
                            <i class="fa-regular fa-comment"></i>
                        </div>
                        <span>{{ $postagem->comentarios->count() }} Comentários</span>
                    </div>
                </div>

                <div class="p-6 bg-white space-y-4">
                    <div class="space-y-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($postagem->comentarios as $comentario)
                            <div class="flex gap-3 animate-fade-in">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400 flex-shrink-0">
                                    {{ substr($comentario->user->name, 0, 1) }}
                                </div>
                                <div class="bg-slate-50 rounded-2xl rounded-tl-none px-4 py-2 flex-1 shadow-sm border border-slate-100">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-black text-slate-700">{{ $comentario->user->name }}</span>
                                        <span class="text-[9px] text-slate-400 font-medium">{{ $comentario->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $comentario->conteudo }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('interacoes.comentar', $postagem->id) }}" method="POST" class="flex gap-2 mt-4">
                        @csrf
                        <input type="text" name="conteudo" placeholder="Escreva algo para os vizinhos..." required
                               class="flex-1 bg-slate-100 border-none rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-xl shadow-md transition-all active:scale-95">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <i class="fa-solid fa-comments text-slate-200 text-6xl mb-4"></i>
                <h3 class="text-slate-400 font-bold">O mural está silencioso hoje...</h3>
                <p class="text-slate-300 text-sm">Seja o primeiro a compartilhar algo com o condomínio!</p>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $interacoes->links() }}
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection