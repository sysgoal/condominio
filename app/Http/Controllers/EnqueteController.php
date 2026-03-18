<?php

namespace App\Http\Controllers;

use App\Models\Enquete;
use App\Models\Voto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnqueteController extends Controller
{
    public function index()
    {
        // Adicionamos o 'opcoes.votos' para ele carregar as relações e evitar lentidão (N+1 query)
        $enquetes = Enquete::with(['opcoes.votos'])->latest()->paginate(10);
        
        return view('enquetes.index', compact('enquetes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_abertura' => 'required|date',
            'data_encerramento' => 'required|date|after:data_abertura',
            'opcoes' => 'required|array|min:2', // Exige pelo menos 2 opções
            'opcoes.*' => 'required|string|max:255'
        ]);

        // Cria a enquete
        $enquete = Enquete::create([
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'data_abertura' => $validated['data_abertura'],
            'data_encerramento' => $validated['data_encerramento'],
        ]);

        // Cria as opções vinculadas à enquete
        foreach ($validated['opcoes'] as $opcaoTexto) {
            $enquete->opcoes()->create(['texto' => $opcaoTexto]);
        }

        return redirect()->route('enquetes.index')->with('success', 'Enquete criada com sucesso!');
    }

    // Lógica para registrar o voto do morador
    public function votar(Request $request, Enquete $enquete)
    {
        $request->validate([
            'enquete_opcao_id' => 'required|exists:enquete_opcaos,id'
        ]);

        // Verifica se a enquete está aberta
        if (now() < $enquete->data_abertura || now() > $enquete->data_encerramento || !$enquete->status) {
            return back()->with('error', 'Esta enquete não está disponível para votação.');
        }

        $userId = Auth::id();

        // Verifica se o usuário já votou em alguma opção DESTA enquete específica
        $jaVotou = Voto::whereHas('opcao', function($q) use ($enquete) {
            $q->where('enquete_id', $enquete->id);
        })->where('user_id', $userId)->exists();

        if ($jaVotou) {
            return back()->with('error', 'Você já registrou seu voto nesta enquete.');
        }

        // Registra o voto
        Voto::create([
            'user_id' => $userId,
            'enquete_opcao_id' => $request->enquete_opcao_id
        ]);

        return back()->with('success', 'Seu voto foi registrado com sucesso!');
    }
}