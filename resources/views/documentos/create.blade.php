@extends('layouts.app')

@section('title', 'Novo Documento / Ata')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center mb-6">
        <a href="{{ route('documentos.index') }}" class="text-slate-500 hover:text-indigo-600 mr-4 transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Publicar Novo Documento</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Título do Documento *</label>
                    <input type="text" name="titulo" required placeholder="Ex: Ata da Assembleia Geral Ordinária 2026" 
                           class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Categoria *</label>
                    <select name="tipo" required id="tipoDocumento" class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                        <option value="ata">Ata de Assembleia</option>
                        <option value="regimento">Regimento Interno</option>
                        <option value="convencao">Convenção do Condomínio</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Data da Emissão/Assembleia *</label>
                    <input type="date" name="data_documento" required 
                           class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Arquivo Original (PDF) - Opcional</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors bg-slate-50">
                    <div class="space-y-1 text-center">
                        <i class="fa-solid fa-file-pdf text-4xl text-slate-400 mb-2"></i>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Faça upload de um arquivo</span>
                                <input name="arquivo" type="file" class="sr-only" accept=".pdf">
                            </label>
                            <p class="pl-1">ou arraste e solte</p>
                        </div>
                        <p class="text-xs text-slate-500">PDF até 5MB</p>
                    </div>
                </div>
            </div>

            <div class="mb-8 bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                <label class="block text-sm font-bold text-blue-800 mb-2">
                    <i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i> Texto da Ata (Para Busca Inteligente)
                </label>
                <p class="text-xs text-blue-600 mb-3">
                    Cole o texto da ata aqui. É isso que permitirá que os moradores pesquisem por termos como "pintura", "multa" ou "vaga de garagem" e encontrem este documento no futuro.
                </p>
                <textarea name="conteudo_texto" rows="8" placeholder="Cole o texto completo do documento aqui..." 
                          class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3 border shadow-sm"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('documentos.index') }}" class="px-6 py-3 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors shadow-sm">
                    Salvar Documento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection