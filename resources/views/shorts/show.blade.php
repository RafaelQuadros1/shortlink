<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $short->short_code }} | {{ config('app.name', 'Laravel') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
    @endif
</head>

<body class="bg-[#0a0a0a] text-white flex flex-col min-h-screen">
    <x-header />
    <main class="w-full max-w-400 sm:max-w-360 lg:w-150 mx-auto flex-1 flex flex-col gap-5 py-8 px-4 sm:px-6">

        <div class="flex items-center justify-end">
            <div class="flex items-center gap-2">
                <a href="{{ route('shorts.edit', $short->encrypted_id) }}"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 text-white text-xs font-medium rounded-lg hover:bg-white/15 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                        <path d="m15 5 4 4" />
                    </svg>
                    Editar
                </a>
                <form method="POST" action="{{ route('shorts.destroy', $short->encrypted_id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 text-white/60 text-xs font-medium rounded-lg hover:bg-red-500/20 hover:text-red-400 transition-colors cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 6h18" />
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                        </svg>
                        Excluir
                    </button>
                </form>
            </div>
        </div>

        <div
            class="w-full bg-white/6 backdrop-blur-2xl border border-white/12 rounded-2xl sm:rounded-[20px] p-5 sm:p-6 flex flex-col gap-5">

            <div class="flex flex-col gap-2">
                <span class="text-white/40 text-xs uppercase tracking-wider">Link curto</span>
                <div class="flex items-center gap-3">
                    <span class="text-white font-semibold text-lg tracking-tight">{{ $short->short_url }}</span>
                    <button onclick="copyToClipboard(this, '{{ $short->short_url }}')"
                        class="shrink-0 p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors cursor-pointer group"
                        title="Copiar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-white/60 group-hover:text-white transition-colors">
                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="w-full h-px bg-white/8"></div>

            <div class="flex flex-col gap-2">
                <span class="text-white/40 text-xs uppercase tracking-wider">URL de destino</span>
                <a href="{{ $short->url_origin }}" target="_blank" rel="noopener noreferrer"
                    class="text-white/70 hover:text-white text-sm break-all transition-colors leading-relaxed">
                    {{ $short->url_origin }}
                </a>
            </div>

            <div class="w-full h-px bg-white/8"></div>

            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <span class="text-white/40 text-xs uppercase tracking-wider">Cliques</span>
                    <span class="text-white font-semibold text-2xl">{{ $short->clicks_count }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-white/40 text-xs uppercase tracking-wider">Criado em</span>
                    <span class="text-white/70 text-sm">{{ $short->created_at->format('d/m/Y') }}</span>
                    <span class="text-white/40 text-xs">{{ $short->created_at->format('H:i') }}</span>
                </div>
            </div>
        </div>

    </main>

    <script>
        function copyToClipboard(button, text) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHTML = button.innerHTML;
                button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><polyline points="20 6 9 17 4 12"/></svg>`;
                button.classList.add('bg-green-500/20');
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-500/20');
                }, 1500);
            });
        }
    </script>
</body>

</html>
