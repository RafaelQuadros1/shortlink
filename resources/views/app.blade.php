<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
        <script src="{{ asset('build/app.js') }}" defer></script>
    @endif
</head>

<body class="bg-[#0a0a0a] text-white flex flex-col min-h-screen">
    <x-header />
    <main
        class="w-full max-w-90 sm:max-w-112.5 lg:w-150 mx-auto flex-1 flex flex-col items-center justify-center gap-4 sm:gap-6">
        <div class="flex flex-col items-center gap-2">
            <div class="flex items-center gap-2">
                @include('icons.link')
                <span class="text-lg sm:text-xl font-semibold text-white">encurta.ai</span>
            </div>
            <p class="text-white/50 text-xs sm:text-sm text-center">Transforme links longos em links curtos e
                rastreáveis</p>
        </div>
        @if (session('error'))
            <div class="w-full max-w-90 sm:max-w-112.5 lg:w-150 bg-red-500/10 border border-red-500/20 rounded-lg px-4 py-3 text-red-400 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif
        <x-form />
        @if (!auth()->check())
            <x-socialite />
        @endif

    </main>
</body>

</html>
