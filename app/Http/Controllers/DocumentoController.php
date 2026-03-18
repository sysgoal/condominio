<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Documento::query();

        // 1. Filtro de Texto (Título e Conteúdo)
        if ($request->filled('busca')) {
            $termo = $request->busca;
            $query->where(function($q) use ($termo) {
                $q->where('titulo', 'like', '%' . $termo . '%')
                  ->orWhere('conteudo_texto', 'like', '%' . $termo . '%');
            });
        }

        // 2. NOVO: Filtro por Tipo de Documento
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $documentos = $query->latest('data_documento')->paginate(10);

        return view('documentos.index', compact('documentos'));
    }

    public function create()
    {
        return view('documentos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:regimento,convencao,ata',
            'titulo' => 'required|string|max:255',
            'conteudo_texto' => 'nullable|string', // Importante para a busca
            'data_documento' => 'required|date',
            'arquivo' => 'nullable|file|mimes:pdf|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('arquivo')) {
            $path = $request->file('arquivo')->store('documentos', 'public');
            $validated['arquivo_path'] = $path;
        }

        Documento::create($validated);

        return redirect()->route('documentos.index')->with('success', 'Documento salvo com sucesso!');
    }

public function destroy(Documento $documento)
{
    // 1. Verifica a permissão via Policy
    $this->authorize('delete', $documento);

    // 2. Apaga o arquivo físico do servidor
    if (Storage::disk('public')->exists($documento->caminho)) {
        Storage::disk('public')->delete($documento->caminho);
    }

    // 3. Deleta o registro do banco de dados
    $documento->delete();

    return back()->with('success', 'Documento removido com sucesso!');
}
}