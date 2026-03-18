@extends('layouts.app')

@section('title', 'Cargos e Funções')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-2">Cargos e Hierarquia</h2>
        <p class="text-slate-500 text-sm mb-8">Defina quem são os administradores, síndicos e membros do conselho.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase font-black tracking-widest border-b border-slate-50">
                        <th class="py-4 px-2">Usuário</th>
                        <th class="py-4 px-2">Função Atual</th>
                        <th class="py-4 px-2 text-right">Alterar Função</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($usuarios as $user)
                    <tr>
                        <td class="py-4 px-2">
                            <div class="font-bold text-slate-800">{{ $user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $user->email }}</div>
                        </td>
                        <td class="py-4 px-2">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter 
                                    {{ $role->name == 'sindico' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="py-4 px-2 text-right">
                            <form action="{{ route('roles.update', $user->id) }}" method="POST" class="flex items-center justify-end gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="text-xs border-slate-200 rounded-lg focus:ring-indigo-500 py-1">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-slate-800 text-white p-1.5 rounded-lg hover:bg-black transition-colors" title="Salvar Alteração">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection