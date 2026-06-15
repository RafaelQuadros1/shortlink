<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Novo Link | {{ config('app.name', 'Laravel') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
    @endif
</head>

<body class="bg-[#0a0a0a] text-white flex flex-col min-h-screen">
    <x-header />
    <main class="w-full max-w-90 sm:max-w-112.5 lg:w-150 mx-auto flex-1 flex flex-col gap-6 py-8 px-4 sm:px-6">
        <div class="flex items-center gap-2">
            @include('icons.link')
            <span class="text-lg sm:text-xl font-semibold text-white">Novo Link</span>
        </div>

        @error('url_origin')
            <div class="px-4 py-2 bg-red-500/10 border border-red-500/20 text-red-500 text-sm rounded-lg">
                {{ $message }}
            </div>
        @enderror

        <form action="{{ route('shorts.store') }}" method="POST"
            class="w-full bg-white/6 backdrop-blur-2xl border border-white/12 rounded-2xl sm:rounded-[20px] p-4 sm:p-6">
            @csrf

            <div class="flex flex-col gap-4">
                <div>
                    <label class="block text-white/60 text-xs sm:text-sm mb-2">URL longa</label>
                    <input type="text" name="url_origin"
                        placeholder="https://exemplo.com/pagina/muito/longa?utm_source" required
                        class="w-full px-2 sm:px-2 py-2 sm:py-2 bg-white/5 border border-white/10 rounded-xl text-white text-sm sm:text-sm placeholder:text-white/25 focus:outline-none focus:bg-white/9 focus:border-white/28 transition-all duration-200">
                </div>

                <div aria-hidden="true" style="position:absolute;left:-9999px;height:0;width:0;overflow:hidden;">
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>
            </div>

            <button type="submit"
                class="w-full mt-4 flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 shrink-0 bg-white text-black font-medium text-sm sm:text-sm rounded-xl hover:opacity-90 hover:-translate-y-px active:scale-[0.98] transition-all duration-150 cursor-pointer">
                Encurtar
            </button>
        </form>
    </main>
    <x-footer />
</body>

</html>
