@extends('layouts.app')

@section('title', 'Gestão de Moradores')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Moradores</h2>
            <p class="text-slate-500 text-sm">Gerencie acessos, status e credenciais dos residentes.</p>
        </div>
        
        @hasanyrole('admin|sindico')
        <a href="{{ route('moradores.create') }}" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg transition-all shadow-sm hover:shadow-md">
            <i class="fa-solid fa-user-plus mr-2"></i> Adicionar Morador
        </a>
        @endhasanyrole
    </div>

    <div class="space-y-4">
       
       

        {{-- Alerta de Nova Senha Gerada --}}
        @if(session('temp_password'))
            <div class="bg-amber-50 border-l-4 border-amber-500 p-5 rounded-r-lg shadow-md animate-pulse">
                <div class="flex">
                    <i class="fa-solid fa-key text-amber-500 mr-4 text-2xl"></i>
                    <div>
                        <p class="text-amber-900 font-black text-lg">Nova senha para {{ session('user_name') }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="bg-white px-4 py-2 rounded font-mono font-bold text-xl border-2 border-amber-200 text-slate-800 shadow-inner">
                                {{ session('temp_password') }}
                            </span>
                            <p class="text-amber-700 text-sm max-w-xs font-medium">
                                Copie e envie agora para o morador. Por segurança, esta senha não será exibida novamente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 font-bold">Morador / E-mail</th>
                        <th class="px-6 py-4 font-bold">Localização</th>
                        <th class="px-6 py-4 font-bold">Contato</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($moradores as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors group {{ !$user->is_active ? 'opacity-60 bg-slate-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full {{ $user->is_active ? 'bg-indigo-100 text-indigo-600 border-indigo-200' : 'bg-slate-200 text-slate-500 border-slate-300' }} flex items-center justify-center font-bold mr-3 border transition-colors">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 {{ $user->is_active ? 'group-hover:text-indigo-600' : '' }} transition-colors">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-slate-700">
                                <i class="fa-solid fa-building text-slate-300 mr-1"></i> 
                                Bloco {{ $user->morador->bloco ?? '-' }}
                            </div>
                            <div class="text-xs text-slate-500 font-medium">Apto {{ $user->morador->apartamento ?? 'N/A' }}</div>
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $user->morador->telefone ?? 'Não informado' }}
                        </td>

                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                    Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                    Inativo
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('moradores.reset', $user->id) }}" method="POST" onsubmit="return confirm('Deseja gerar uma nova senha aleatória para este morador?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center w-9 h-9 text-amber-600 hover:text-white bg-amber-50 hover:bg-amber-500 rounded-lg transition-all" title="Resetar Senha">
                                        <i class="fa-solid fa-key"></i>
                                    </button>
                                </form>

                                <form action="{{ route('moradores.status', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-9 h-9 {{ $user->is_active ? 'text-slate-400 hover:bg-slate-500' : 'text-green-600 hover:bg-green-500' }} hover:text-white bg-white border border-slate-200 rounded-lg transition-all" 
                                            title="{{ $user->is_active ? 'Desativar Acesso' : 'Ativar Acesso' }}">
                                        <i class="fa-solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    </button>
                                </form>
                                <button type="button" 
                                onclick="openRoleModal('{{ $user->id }}', '{{ $user->name }}', {{ $user->roles->pluck('id') }})"
                                class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-lg transition-colors"
                                title="Gerenciar Permissões">
                            <i class="fa-solid fa-user-shield"></i>
                        </button>
                                <a href="{{ route('moradores.edit', $user->id) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 text-indigo-600 hover:text-white bg-indigo-50 hover:bg-indigo-600 rounded-lg transition-all" 
                                   title="Editar Morador">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('moradores.destroy', $user->id) }}" method="POST" onsubmit="return confirm('ATENÇÃO: Deseja realmente EXCLUIR este morador permanentemente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-9 h-9 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 rounded-lg transition-all" title="Excluir Definitivamente">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i class="fa-solid fa-users-slash text-4xl text-slate-200 mb-3"></i>
                            <p class="text-slate-500">Nenhum morador cadastrado.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($moradores->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                {{ $moradores->links() }}
            </div>
        @endif
    </div>
</div>
<div id="roleModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <form id="roleForm" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-xl font-bold text-slate-800" id="modalUserName">Nome do Usuário</h3>
                <p class="text-sm text-slate-500">Selecione as funções deste morador no sistema.</p>
            </div>

            <div class="p-6 space-y-4">
                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                <label class="flex items-center p-3 rounded-xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-all">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                           id="role_{{ $role->id }}"
                           class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <div class="ml-3">
                        <span class="block text-sm font-bold text-slate-700 uppercase">{{ $role->name }}</span>
                        <span class="block text-[10px] text-slate-400">Permissões de {{ $role->name }} ativas</span>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="p-6 bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="closeRoleModal()" class="px-4 py-2 text-sm font-bold text-slate-500">Cancelar</button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm shadow-md hover:bg-indigo-700 transition-all">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRoleModal(userId, userName, userRoles) {
        document.getElementById('modalUserName').innerText = userName;
        document.getElementById('roleForm').action = `/configuracoes/permissoes/${userId}`;
        
        // Resetar checkboxes
        document.querySelectorAll('#roleForm input[type="checkbox"]').forEach(el => el.checked = false);
        
        // Marcar as que o usuário já tem
        userRoles.forEach(roleId => {
            const check = document.getElementById(`role_${roleId}`);
            if(check) check.checked = true;
        });

        document.getElementById('roleModal').classList.remove('hidden');
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.add('hidden');
    }
</script>
@endsection
