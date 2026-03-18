<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleAssignmentController extends Controller
{
    public function index()
    {
        // Lista todos os usuários e todas as funções disponíveis
        $usuarios = User::with('roles')->paginate(10);
        $roles = Role::all();
        
        return view('admin.roles.index', compact('usuarios', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Se não vier nenhuma role selecionada, enviamos um array vazio
        $roles = $request->input('roles', []);
    
        // syncRoles remove o que não está no array e adiciona o que está
        $user->syncRoles($roles);
    
        return back()->with('success', "Permissões de {$user->name} atualizadas com sucesso!");
    }
}