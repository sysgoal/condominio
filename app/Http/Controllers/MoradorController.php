<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Morador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str; 
use App\Notifications\NovaSenhaMorador; 
use Illuminate\Support\Facades\DB;
use App\Notifications\BoasVindasMorador;

class MoradorController extends Controller
{
    public function index()
    {
        // Busca todos os usuários que têm o papel de 'morador', 
        // trazendo junto os dados da tabela 'moradores' (bloco, apto)
        $moradores = User::role('morador')->with('morador')->latest()->paginate(15);
        
        return view('moradores.index', compact('moradores'));
    }

    public function create()
    {
        return view('moradores.create');
    }

    public function store(Request $request)
    {
        // 1. Validação dos dados
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', \Illuminate\Validation\Rules\Password::defaults()],
            'bloco' => ['nullable', 'string', 'max:50'],
            'apartamento' => ['required', 'string', 'max:50'],
            'telefone' => ['nullable', 'string', 'max:20'],
        ]);

       
       
        try {
            return DB::transaction(function () use ($request) {
                // 1. Gerar senha
                $senhaTemporaria = Str::random(8);

                // 2. Criar o Usuário
        // 2. Cria o Usuário (Acesso ao sistema)
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($senhaTemporaria),
                    'is_active' => true,
                ]);

                // 3. Criar o registro na tabela moradores (vinculando ao user_id)
        Morador::create([
            'user_id' => $user->id,
            'bloco' => $request->bloco,
            'apartamento' => $request->apartamento,
            'telefone' => $request->telefone,
        ]);
                // 4. Atribuir Cargo
                $user->assignRole('morador');

                // 5. DISPARAR E-MAIL
                $user->notify(new BoasVindasMorador($senhaTemporaria));

                return redirect()->route('moradores.index')
                    ->with('success', 'Morador cadastrado e e-mail enviado!');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        // Carrega o usuário junto com os dados da tabela 'morador'
        $user->load('morador');
        return view('moradores.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // 1. Validação (Ignora o e-mail do próprio usuário na verificação de 'unique')
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'bloco' => ['nullable', 'string', 'max:50'],
            'apartamento' => ['required', 'string', 'max:50'],
            'telefone' => ['nullable', 'string', 'max:20'],
        ]);

        // 2. Atualiza os dados básicos (User)
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // 3. Atualiza os dados do condomínio (Morador)
        $user->morador()->update([
            'bloco' => $request->bloco,
            'apartamento' => $request->apartamento,
            'telefone' => $request->telefone,
        ]);

        return redirect()->route('moradores.index')->with('success', 'Dados do morador atualizados!');
    }

    public function destroy(User $user)
    {
        // 1. Remove os dados da tabela 'moradores' primeiro (por causa da chave estrangeira)
        $user->morador()->delete();
        
        // 2. Remove o usuário do sistema
        $user->delete();

        return redirect()->route('moradores.index')->with('success', 'Morador removido permanentemente.');
    }
// ... dentro da classe ...

public function toggleStatus(User $user)
{
    // Inverte o valor (se era 1 vira 0, se era 0 vira 1)
    $user->is_active = !$user->is_active;
    $user->save(); // Força a gravação no banco

    $status = $user->is_active ? 'ativado' : 'desativado';
    return back()->with('success', "Morador $status com sucesso!");
}


public function resetPassword(User $user)
{
    $novaSenha = Str::random(8);
    
    $user->update([
        'password' => Hash::make($novaSenha),
    ]);

    // Dispara o e-mail para o morador
    $user->notify(new NovaSenhaMorador($novaSenha, $user->name));

    return back()
        ->with('temp_password', $novaSenha)
        ->with('user_name', $user->name)
        ->with('success', 'Nova senha gerada e enviada por e-mail para o morador!');
}
}