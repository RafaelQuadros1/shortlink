<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Link não encontrado | {{ config('app.name', 'Laravel') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
    @endif
</head>

<body class="bg-[#0a0a0a] text-white flex flex-col min-h-screen">
    <x-header />
    <main class="w-full max-w-400 sm:max-w-360 lg:w-150 mx-auto flex-1 flex flex-col items-center justify-center gap-6 px-4">
        <span class="text-7xl sm:text-8xl font-bold text-white/10">404</span>
        <div class="flex flex-col items-center gap-2 text-center">
            <h1 class="text-xl sm:text-2xl font-semibold">Link não encontrado</h1>
            <p class="text-white/50 text-sm sm:text-base max-w-md">
                Esse código curto não existe ou foi removido.
            </p>
        </div>
        <div class="flex items-center gap-3 mt-4">
            <a href="{{ route('home') }}"
                class="px-6 py-2.5 bg-white text-[#0a0a0a] rounded-lg font-medium text-sm hover:bg-white/90 transition-colors">
                Criar um link
            </a>
            @auth
                <a href="{{ route('shorts.index') }}"
                    class="px-6 py-2.5 bg-white/10 text-white rounded-lg font-medium text-sm hover:bg-white/15 transition-colors">
                    Meus links
                </a>
            @endauth
        </div>
    </main>
</body>

</html>
