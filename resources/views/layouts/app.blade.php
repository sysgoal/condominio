<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CondoAdmin - Gestão Inteligente</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        @media print {
            nav, aside, .print\:hidden, button, footer {
                display: none !important;
            }
            body { background: white !important; }
            .container, main { width: 100% !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
            .shadow-sm, .rounded-xl { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        }

        /* Suaviza a rolagem da sidebar no mobile */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #4338ca; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden">
    </div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-indigo-950 text-white flex flex-col shadow-2xl transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 lg:shadow-none">
        
        <div class="h-16 flex items-center justify-between px-6 border-b border-indigo-900/50 bg-indigo-950 shrink-0">
            <h1 class="text-xl font-bold tracking-wider flex items-center">
                <i class="fa-solid fa-building-circle-check mr-2 text-indigo-400"></i> CondoAdmin
            </h1>
            <button @click="sidebarOpen = false" class="lg:hidden text-indigo-300 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto custom-scrollbar">
            <p class="px-4 text-[10px] font-black uppercase text-indigo-400 tracking-widest mb-2 opacity-50">Principal</p>
            
            @hasanyrole('admin|sindico')
            <a href="{{ route('moradores.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('moradores.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-indigo-900 hover:text-white' }} rounded-xl transition-all text-sm font-medium group">
                <i class="fa-solid fa-users w-6 group-hover:scale-110 transition-transform"></i> Moradores
            </a>
            @endhasanyrole

            <a href="{{ route('contas.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('contas.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-indigo-900 hover:text-white' }} rounded-xl transition-all text-sm font-medium group">
                <i class="fa-solid fa-file-invoice-dollar w-6 group-hover:scale-110 transition-transform"></i> Financeiro
            </a>

            <a href="{{ route('interacoes.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('interacoes.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-indigo-900 hover:text-white' }} rounded-xl transition-all text-sm font-medium group">
                <i class="fa-solid fa-comments w-6 group-hover:scale-110 transition-transform"></i> Mural Social
            </a>

            <a href="{{ route('enquetes.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('enquetes.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-indigo-900 hover:text-white' }} rounded-xl transition-all text-sm font-medium group">
                <i class="fa-solid fa-check-to-slot w-6 group-hover:scale-110 transition-transform"></i> Enquetes
            </a>

            <a href="{{ route('documentos.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('documentos.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-indigo-900 hover:text-white' }} rounded-xl transition-all text-sm font-medium group">
                <i class="fa-solid fa-folder-open w-6 group-hover:scale-110 transition-transform"></i> Documentos e Atas
            </a>
        </nav>
        
        <div class="p-4 border-t border-indigo-900/50 bg-indigo-950/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 text-indigo-400 hover:text-rose-400 hover:bg-rose-400/10 rounded-xl transition-all text-sm font-bold group">
                    <i class="fa-solid fa-right-from-bracket mr-2 group-hover:-translate-x-1 transition-transform"></i> Sair do Sistema
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 md:px-8 z-10 shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="text-lg md:text-xl font-bold text-slate-700 truncate">@yield('title', 'Painel')</h2>
            </div>

            <div class="flex items-center space-x-3 md:space-x-4">
                <div class="hidden sm:flex flex-col items-end leading-tight">
                    <span class="text-sm font-bold text-slate-800">{{ Auth::user()->name ?? 'Usuário' }}</span>
                    <span class="text-[10px] uppercase font-black text-indigo-500 tracking-tighter">Conectado</span>
                </div>
                <a href="{{ route('profile.edit') }}" class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm group">
                    <i class="fa-solid fa-user group-hover:scale-110 transition-transform"></i>
                </a>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 md:p-8">
            <div class="max-w-7xl mx-auto">
                
                @if(session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 mb-6 rounded-xl shadow-sm flex items-center animate-bounce-short">
                        <i class="fa-solid fa-circle-check mr-3 text-emerald-500 text-xl"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 mb-6 rounded-xl shadow-sm flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-3 text-rose-500 text-xl"></i>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-rose-400 hover:text-rose-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
                
                @if(isset($slot))
                    {{ $slot }}
                @endif

            </div>
        </main>
    </div>

</body>
</html>