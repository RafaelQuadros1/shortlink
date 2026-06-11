<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>404 - Página não encontrada | {{ config('app.name', 'Laravel') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
    @endif
</head>

<body class="bg-[#0a0a0a] text-white flex flex-col min-h-screen items-center justify-center">
    <div class="flex flex-col items-center gap-6 text-center px-4">
        <span class="text-7xl sm:text-8xl font-bold text-white/10">404</span>
        <div class="flex flex-col items-center gap-2">
            <h1 class="text-xl sm:text-2xl font-semibold">Página não encontrada</h1>
            <p class="text-white/50 text-sm sm:text-base max-w-md">
                O link que você tentou acessar não existe ou foi movido.
            </p>
        </div>
        <a href="{{ route('home') }}"
            class="mt-4 px-6 py-2.5 bg-white text-[#0a0a0a] rounded-lg font-medium text-sm hover:bg-white/90 transition-colors">
            Voltar para o início
        </a>
    </div>
</body>

</html>
