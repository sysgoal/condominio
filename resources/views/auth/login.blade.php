<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso - CondoAdmin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900 overflow-hidden">

    <div class="flex min-h-screen">
        
        <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=1000&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-indigo-900/60 mix-blend-multiply"></div>
            
            <div class="absolute bottom-0 left-0 p-16 text-white w-full bg-gradient-to-t from-indigo-900/90 to-transparent">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm text-white text-2xl mb-6 border border-white/30">
                    <i class="fa-solid fa-building"></i>
                </div>
                <h2 class="text-4xl font-bold mb-4 leading-tight">Bem-vindo ao seu <br>condomínio digital.</h2>
                <p class="text-indigo-100 text-lg max-w-md">Gestão transparente, comunicação rápida e todas as informações na palma da sua mão.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white shadow-[0_0_40px_rgba(0,0,0,0.1)] z-10 relative">
            <div class="w-full max-w-md space-y-8">
                
                <div class="text-center lg:text-left">
                    <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Acesse sua conta</h1>
                    <p class="text-slate-500 mt-2">Digite suas credenciais para entrar no painel.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6 mt-8">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm font-medium flex items-center">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                            E-mail ou senha incorretos. Tente novamente.
                        </div>
                    @endif

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">E-mail cadastrado</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <i class="fa-regular fa-envelope text-slate-400"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full pl-10 p-3.5 transition-colors" placeholder="seu@email.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">Senha</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Esqueceu a senha?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full pl-10 p-3.5 transition-colors" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 bg-slate-50 border-slate-300 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer">
                        <label for="remember_me" class="ml-2 text-sm text-slate-600 font-medium cursor-pointer">Lembrar meus dados neste computador</label>
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center py-4 px-4 rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:shadow-lg hover:-translate-y-0.5 mt-2">
                        Entrar no Sistema <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i>
                    </button>
                </form>

                <p class="text-center text-xs text-slate-400 mt-8">
                    &copy; {{ date('Y') }} CondoAdmin. Todos os direitos reservados.
                </p>

            </div>
        </div>
    </div>

</body>
</html>