<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CondoAdmin - Gestão Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-indigo-900 text-white flex flex-col shadow-xl">
        <div class="h-16 flex items-center justify-center border-b border-indigo-800">
            <h1 class="text-xl font-bold tracking-wider"><i class="fa-solid fa-building mr-2"></i> CondoAdmin</h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="#" class="flex items-center px-4 py-3 bg-indigo-800 rounded-lg transition-colors">
                <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
            </a>
            <a href="{{ route('contas.index') }}" class="flex items-center px-4 py-3 hover:bg-indigo-800 rounded-lg transition-colors">
                <i class="fa-solid fa-file-invoice-dollar w-6"></i> Financeiro
            </a>
            <a href="{{ route('interacoes.index') }}" class="flex items-center px-4 py-3 hover:bg-indigo-800 rounded-lg transition-colors">
                <i class="fa-solid fa-comments w-6"></i> Mural Social
            </a>
            <a href="{{ route('enquetes.index') }}" class="flex items-center px-4 py-3 hover:bg-indigo-800 rounded-lg transition-colors">
                <i class="fa-solid fa-check-to-slot w-6"></i> Enquetes
            </a>
            <a href="{{ route('documentos.index') }}" class="flex items-center px-4 py-3 hover:bg-indigo-800 rounded-lg transition-colors">
                <i class="fa-solid fa-folder-open w-6"></i> Documentos e Atas
            </a>
        </nav>
        <div class="p-4 border-t border-indigo-800 text-sm text-indigo-300">
            &copy; 2026 CondoAdmin
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h2 class="text-xl font-semibold text-slate-700">@yield('title', 'Painel')</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-slate-500">Olá, Usuário</span>
                <div class="w-10 h-10 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center text-indigo-600 font-bold">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>