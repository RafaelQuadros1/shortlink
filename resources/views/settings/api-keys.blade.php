<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Configurações | {{ config('app.name', 'Laravel') }}</title>

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
            @include('icons.back')
            <a href="{{ route('home') }}" class="text-lg sm:text-xl font-semibold text-white hover:underline">Configurações</a>
        </div>

        @if (session('success'))
            <div class="w-full px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('plain_key'))
            <div class="w-full bg-blue-500/10 border border-blue-500/20 rounded-2xl sm:rounded-[20px] p-4 sm:p-6">
                <p class="text-blue-400 text-sm font-medium mb-2">Sua nova API Key (guarde em local seguro):</p>
                <div class="flex items-center gap-2">
                    <code id="plain-key" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-white text-sm font-mono break-all">{{ session('plain_key') }}</code>
                    <button data-copy-key class="text-white/40 hover:text-white transition-colors cursor-pointer shrink-0" title="Copiar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                    </button>
                </div>
                <p class="text-blue-400/60 text-xs mt-2">Esta chave não será mostrada novamente.</p>
            </div>
        @endif

        <div class="w-full bg-white/6 backdrop-blur-2xl border border-white/12 rounded-2xl sm:rounded-[20px] p-4 sm:p-6">
            <h3 class="text-white/70 text-xs uppercase tracking-wider mb-4">API Keys</h3>

            @if ($apiKeys->isEmpty())
                <p class="text-white/30 text-sm text-center py-4">Nenhuma API key criada.</p>
            @else
                <div class="flex flex-col gap-3">
                    @foreach ($apiKeys as $apiKey)
                        <div class="w-full bg-white/5 border border-white/10 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex flex-col gap-1 min-w-0">
                                <span class="text-white font-medium text-sm">{{ $apiKey->name }}</span>
                                <div class="flex items-center gap-2">
                                    <code class="text-white/40 text-xs font-mono">sk_****{{ substr($apiKey->key, -4) }}</code>
                                    @if ($apiKey->last_used_at)
                                        <span class="text-white/30 text-xs">· usado {{ $apiKey->last_used_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                            <form method="POST" action="{{ route('settings.api-keys.destroy', $apiKey->id) }}" class="shrink-0"
                                onsubmit="return confirm('Tem certeza que deseja revogar esta chave?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white/40 hover:text-red-400 text-xs transition-colors cursor-pointer">
                                    Revogar
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="w-full bg-white/6 backdrop-blur-2xl border border-white/12 rounded-2xl sm:rounded-[20px] p-4 sm:p-6">
            <h3 class="text-white/70 text-xs uppercase tracking-wider mb-4">Gerar nova chave</h3>
            <form method="POST" action="{{ route('settings.api-keys.store') }}" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label class="block text-white/60 text-xs sm:text-sm mb-2">Nome da chave</label>
                    <input type="text" name="name" placeholder="Meu App" required
                        class="w-full px-2 sm:px-2 py-2 sm:py-2 bg-white/5 border border-white/10 rounded-xl text-white text-sm sm:text-sm placeholder:text-white/25 focus:outline-none focus:bg-white/9 focus:border-white/28 transition-all duration-200">
                </div>
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 shrink-0 bg-white text-black font-medium text-sm sm:text-sm rounded-xl hover:opacity-90 hover:-translate-y-px active:scale-[0.98] transition-all duration-150 cursor-pointer">
                    Gerar chave
                </button>
            </form>
        </div>

    </main>

    <script nonce="{{ $cspNonce }}">
        document.querySelectorAll('[data-copy-key]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var key = document.getElementById('plain-key').textContent;
                var button = this;
                navigator.clipboard.writeText(key).then(function() {
                    var originalHTML = button.innerHTML;
                    button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><polyline points="20 6 9 17 4 12"/></svg>';
                    setTimeout(function() { button.innerHTML = originalHTML; }, 1500);
                }).catch(function() {});
            });
        });
    </script>
    <x-footer />
</body>

</html>
