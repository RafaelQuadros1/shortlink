@php
    $exampleId = 'example-' . uniqid();
@endphp

<div class="w-full">
    <button data-target="{{ $exampleId }}"
        class="example-toggle w-full flex items-center justify-between px-4 py-3 bg-white/6 backdrop-blur-2xl border border-white/12 rounded-2xl sm:rounded-[20px] hover:bg-white/9 transition-all duration-200 cursor-pointer">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-white/50">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
            <span class="text-white/70 text-sm">Como usar a API</span>
        </div>
        <svg data-chevron="{{ $exampleId }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="text-white/40 transition-transform duration-200">
            <polyline points="6 9 12 15 18 9" />
        </svg>
    </button>

    <div id="{{ $exampleId }}"
        class="hidden mt-3 bg-white/6 backdrop-blur-2xl border border-white/12 rounded-2xl sm:rounded-[20px] overflow-hidden">
        <p class="px-4 pt-4 text-white/50 text-xs sm:text-sm">Este endpoint recebe JSON com o campo <code
                class="bg-white/10 px-1 rounded">url_origin</code>.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-sm mt-3">
                <thead>
                    <tr class="border-t border-b border-white/10">
                        <th class="text-left px-4 py-2 text-white/50 font-medium text-xs">Método</th>
                        <th class="text-left px-4 py-2 text-white/50 font-medium text-xs">Headers</th>
                        <th class="text-left px-4 py-2 text-white/50 font-medium text-xs">Body</th>
                        <th class="text-left px-4 py-2 text-white/50 font-medium text-xs">Endpoint</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-white/5">
                        <td class="px-4 py-3 text-green-400 font-mono font-bold text-xs">POST</td>
                        <td class="px-4 py-3 text-white/60 text-xs">X-API-Key</td>
                        <td class="px-4 py-3 text-white/60 text-xs">{ "url_origin": "..." }</td>
                        <td class="px-4 py-3 text-white/60 text-xs font-mono break-all">{{ url('/api/v1/shorts') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-4 pb-4 pt-3">
            <span class="text-white/50 text-xs uppercase tracking-wider">Exemplo <span
                    class="text-white/30 normal-case">(JavaScript / fetch)</span></span>
            <pre
                class="mt-3 bg-white/5 border border-white/10 rounded-xl p-3 text-white/60 text-xs overflow-x-auto"><code><span class="text-purple-400">const</span> <span class="text-blue-300">response</span> = <span class="text-purple-400">await</span> <span class="text-yellow-300">fetch</span>(<span class="text-green-400">'{{ url('/api/v1/shorts') }}'</span>, {
  <span class="text-blue-300">method</span>: <span class="text-green-400">'POST'</span>,
  <span class="text-blue-300">headers</span>: {
    <span class="text-green-400">'X-API-Key'</span>: <span class="text-green-400">'sk_sua_chave_aqui'</span>,
  },
  <span class="text-blue-300">body</span>: <span class="text-yellow-300">JSON</span>.<span class="text-yellow-300">stringify</span>({ <span class="text-blue-300">url_origin</span>: <span class="text-green-400">'https://exemplo.com'</span> }),
});

<span class="text-purple-400">const</span> <span class="text-blue-300">data</span> = <span class="text-purple-400">await</span> response.<span class="text-yellow-300">json</span>();
console.<span class="text-yellow-300">log</span>(data.<span class="text-blue-300">data</span>.<span class="text-blue-300">short_url</span>);</code></pre>
        </div>

        <div class="border-t border-white/10 px-4 py-3">
            <span class="text-white/40 text-xs">Sua API key está em <a href="{{ route('settings.api-keys') }}"
                    class="text-white/60 hover:text-white underline">configurações</a>.</span>
        </div>
    </div>
</div>
