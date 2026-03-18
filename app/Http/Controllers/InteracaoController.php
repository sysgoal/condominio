<?php
namespace App\Http\Controllers;

use App\Models\Interacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comentario; // Se você criou esse modelo

class InteracaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Interacao::with('autor')->where('status', true);

        // Filtro por tipo (discussão, venda, aviso)
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $interacoes = $query->latest()->paginate(15);

        return view('interacoes.index', compact('interacoes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:discussao,venda,aviso',
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'preco' => 'nullable|required_if:tipo,venda|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();

        Interacao::create($validated);

        return redirect()->route('interacoes.index')->with('success', 'Publicação criada com sucesso!');
    }

    public function destroy(Interacao $interacao)
{
    // O Laravel verifica automaticamente a Policy aqui
    $this->authorize('delete', $interacao);

    $interacao->delete();

    return back()->with('success', 'Publicação removida com sucesso!');
}


// ... dentro da classe ...

public function curtir(Interacao $interacao)
{
    $interacao->increment('curtidas');
    return back();
}

public function comentar(Request $request, Interacao $interacao)
{
    $request->validate([
        'conteudo' => 'required|string|max:500',
    ]);

    $interacao->comentarios()->create([
        'user_id' => auth()->id(),
        'conteudo' => $request->conteudo,
    ]);

    return back()->with('success', 'Comentário enviado!');
}
}