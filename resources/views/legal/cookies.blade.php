<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Politica de Cookies | {{ config('app.name', 'Laravel') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
    @endif
</head>

<body class="bg-[#0a0a0a] text-white flex flex-col min-h-screen">
    <x-header />
    <main class="w-full max-w-90 sm:max-w-112.5 lg:w-150 mx-auto flex-1 flex flex-col gap-6 py-8 px-4 sm:px-6">
        <h1 class="text-2xl sm:text-3xl font-semibold">Politica de Cookies</h1>

        <div class="flex flex-col gap-4 text-white/70 text-sm leading-relaxed">
            <p><strong class="text-white">Ultima atualizacao:</strong> 12 de junho de 2026</p>

            <h2 class="text-lg font-semibold text-white pt-2">1. O que sao cookies</h2>
            <p>
                Cookies sao pequenos arquivos de texto armazenados no seu dispositivo quando voce acessa um site.
                Eles sao amplamente utilizados para fazer os sites funcionarem de forma mais eficiente e fornecer
                informacoes aos proprietarios do site.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">2. Como utilizamos cookies</h2>
            <p>Utilizamos cookies nas seguintes categorias:</p>

            <h3 class="text-base font-semibold text-white pt-1">2.1 Cookies Necessarios (sempre ativos)</h3>
            <p>Essenciais para o funcionamento do site. Nao podem ser desativados.</p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-white/10 rounded-lg">
                    <thead>
                        <tr class="bg-white/5">
                            <th class="px-4 py-2 text-left text-white/60">Cookie</th>
                            <th class="px-4 py-2 text-left text-white/60">Finalidade</th>
                            <th class="px-4 py-2 text-left text-white/60">Duracao</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <tr>
                            <td class="px-4 py-2">shortlink-session</td>
                            <td class="px-4 py-2">Sessao do usuario (autenticacao)</td>
                            <td class="px-4 py-2">2 horas</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">XSRF-TOKEN</td>
                            <td class="px-4 py-2">Protecao contra ataques CSRF</td>
                            <td class="px-4 py-2">Sessao</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">cc_cookie</td>
                            <td class="px-4 py-2">Registro de preferencias de cookies</td>
                            <td class="px-4 py-2">182 dias</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="text-base font-semibold text-white pt-1">2.3 Cookies de Analise (opcional)</h3>
            <p>
                Utilizados para entender como os visitantes interagem com o site. Todas as informacoes sao
                anonimizadas. So serao ativados se voce der seu consentimento.
            </p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-white/10 rounded-lg">
                    <thead>
                        <tr class="bg-white/5">
                            <th class="px-4 py-2 text-left text-white/60">Cookie</th>
                            <th class="px-4 py-2 text-left text-white/60">Finalidade</th>
                            <th class="px-4 py-2 text-left text-white/60">Duracao</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <tr>
                            <td class="px-4 py-2">_ga</td>
                            <td class="px-4 py-2">Google Analytics - Distincao de usuarios</td>
                            <td class="px-4 py-2">2 anos</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">_gid</td>
                            <td class="px-4 py-2">Google Analytics - Distincao de usuarios</td>
                            <td class="px-4 py-2">24 horas</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2 class="text-lg font-semibold text-white pt-2">3. Gerenciamento de cookies</h2>
            <p>
                Voce pode gerenciar suas preferencias de cookies a qualquer momento clicando no botao
                "Gerenciar cookies" no rodape de qualquer pagina do site, ou acessando as configuracoes do seu
                navegador.
            </p>

            <h3 class="text-base font-semibold text-white pt-1">Como desativar cookies no navegador:</h3>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li><strong class="text-white">Chrome:</strong> Configuracoes > Privacidade e Seguranca > Cookies</li>
                <li><strong class="text-white">Firefox:</strong> Configuracoes > Privacidade e Seguranca > Cookies e
                    Dados de Site</li>
                <li><strong class="text-white">Safari:</strong> Preferencias > Privacidade > Gerenciar Dados de Site</li>
                <li><strong class="text-white">Edge:</strong> Configuracoes > Privacidade > Cookies</li>
            </ul>
            <p>
                Observe que desativar cookies pode afetar o funcionamento adequado do site.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">4. Alteracoes nesta politica</h2>
            <p>
                Podemos atualizar esta Politica de Cookies periodicamente. Quando houver mudancas significativas,
                exibiremos um novo banner de consentimento para que voce possa revisar suas preferencias.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">5. Contato</h2>
            <p>
                Para esclarecer duvidas sobre esta Politica de Cookies, entre em contato:
            </p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>E-mail: [seu-email@exemplo.com]</li>
            </ul>
        </div>
    </main>
    <x-footer />
</body>

</html>
