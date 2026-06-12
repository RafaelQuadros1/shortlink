<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Meus Links | {{ config('app.name', 'Laravel') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
    @endif
</head>

<body class="bg-[#0a0a0a] text-white flex flex-col min-h-screen">
    <x-header />
    <main class="w-full max-w-400 sm:max-w-360 lg:w-150 mx-auto flex-1 flex flex-col gap-6 py-8 px-4 sm:px-6">
        <div class="flex items-center justify-end">
            <a href="{{ route('shorts.create') }}"
                class="flex items-center gap-2 px-4 py-2 bg-white text-black font-medium text-sm rounded-xl hover:opacity-90 hover:-translate-y-px active:scale-[0.98] transition-all duration-150">
                Novo Link
            </a>
        </div>

        @if ($shorts->isEmpty())
            <div class="w-full bg-white/6 backdrop-blur-2xl border border-white/12 rounded-2xl sm:rounded-[20px] p-8 text-center">
                <p class="text-white/50 text-sm">Voce ainda nao criou nenhum link curto.</p>
            </div>
        @else
            <div class="flex flex-col gap-3">
                @foreach ($shorts as $short)
                    <div
                        class="w-full bg-white/6 backdrop-blur-2xl border border-white/12 rounded-2xl sm:rounded-[20px] p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex flex-col gap-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('shorts.show', $short->encrypted_id) }}"
                                    class="text-white font-medium text-sm truncate hover:underline">{{ $short->short_url }}</a>
                                <button onclick="copyToClipboard(this, @js($short->short_url))"
                                    class="text-white/40 hover:text-white transition-colors cursor-pointer shrink-0"
                                    title="Copiar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                                    </svg>
                                </button>
                            </div>
                            <span class="text-white/40 text-xs truncate">{{ $short->url_origin }}</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-white/30 text-xs flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M15 3h6v6" />
                                    <path d="M10 14 21 3" />
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                </svg>
                                {{ $short->clicks_count }}
                            </span>
                            <span
                                class="text-white/30 text-xs">{{ $short->created_at->diffForHumans() }}</span>
                            <a href="{{ route('shorts.edit', $short->encrypted_id) }}"
                                class="text-white/30 hover:text-white transition-colors"
                                title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                    <path d="m15 5 4 4" />
                                </svg>
                            </a>
                            <a href="{{ route('shorts.show', $short->encrypted_id) }}"
                                class="text-white/30 hover:text-white transition-colors"
                                title="Ver detalhes">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('shorts.destroy', $short->encrypted_id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-white/30 hover:text-red-500 transition-colors cursor-pointer"
                                    title="Excluir">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-2">
                {{ $shorts->links() }}
            </div>
        @endif
    </main>

    <script>
        function copyToClipboard(button, text) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHTML = button.innerHTML;
                button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><polyline points="20 6 9 17 4 12"/></svg>`;
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                }, 1500);
            });
        }
    </script>
    <x-footer />
</body>

</html>
