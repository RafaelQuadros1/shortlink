<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Termos de Uso | {{ config('app.name', 'Laravel') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
    @endif
</head>

<body class="bg-[#0a0a0a] text-white flex flex-col min-h-screen">
    <x-header />
    <main class="w-full max-w-100 sm:max-w-200 lg:w-150 mx-auto flex-1 flex flex-col gap-6 py-8 px-4 sm:px-6">
        <h1 class="text-2xl sm:text-3xl font-semibold">Termos de Uso</h1>

        <div class="flex flex-col gap-4 text-white/70 text-sm leading-relaxed">
            <p><strong class="text-white">Ultima atualizacao:</strong> 12 de junho de 2026</p>

            <h2 class="text-lg font-semibold text-white pt-2">1. Aceitacao dos termos</h2>
            <p>
                Ao acessar e utilizar o <strong class="text-white">encurta.ai</strong>, voce concorda com estes
                Termos de Uso. Se nao concordar com algum dos termos, nao utilize o servico.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">2. Descricao do servico</h2>
            <p>
                O encurta.ai e um servico gratuito de encurtamento de URLs que permite aos usuarios criar links
                curtos, rastrear cliques e gerenciar seus links encurtados.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">3. Cadastro e conta</h2>
            <p>Para acessar funcionalidades completas, e necessario criar uma conta via autenticacao do Google.</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>Voce e responsavel pela seguranca da sua conta.</li>
                <li>Concorda em fornecer informacoes verdadeiras durante o cadastro.</li>
                <li>Notifique-nos imediatamente sobre uso nao autorizado da sua conta.</li>
            </ul>

            <h2 class="text-lg font-semibold text-white pt-2">4. Uso aceitavel</h2>
            <p>Ao utilizar o servico, voce concorda em NAO:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>Criar links para conteudos ilegais, fraudulentos ou que violem direitos de terceiros.</li>
                <li>Usar o servico para phishing, spam ou distribuicao de malware.</li>
                <li>Tentar explorar falhas de seguranca ou acessar areas nao autorizadas.</li>
                <li>Realizar engenharia reversa, descompilar ou desobfuscar o codigo do servico.</li>
                <li>Automatizar o servico de forma abusiva (bots, scripts de scraping excessivo).</li>
                <li>Usar o servico para encurtar URLs que redirecionam para sites de golpe ou conteudo malicioso.</li>
            </ul>

            <h2 class="text-lg font-semibold text-white pt-2">5. Propriedade intelectual</h2>
            <p>
                Todo o conteudo, design, codigo-fonte e marcas do encurta.ai sao protegidos por direitos autorais
                e propriedade intelectual. Voce pode usar o servico conforme estes termos, mas nao pode copiar,
                modificar ou redistribuir qualquer parte do servico.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">6. Conteudo do usuario</h2>
            <p>
                Voce mantem a propriedade sobre as URLs que encurta. Ao utilizar o servico, voce nos concede
                uma licenca limitada para exibir e processar seu conteudo exclusivamente para operacao do servico.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">7. Disponibilidade do servico</h2>
            <p>
                Nos esforcamos para manter o servico disponivel, mas nao garantimos disponibilidade 100%.
                Podemos realizar manutencao programada ou nao programada sem aviso previo.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">8. Limitacao de responsabilidade</h2>
            <p>
                O encurta.ai e fornecido "como esta". Nao nos responsabilizamos por:
            </p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>Perdas financeiras decorrentes do uso do servico.</li>
                <li>Interrupcoes ou indisponibilidade temporaria.</li>
                <li>Acoes de terceiros realizadas atraves de links encurtados.</li>
                <li>Danos indiretos, incidentais ou consequenciais.</li>
            </ul>

            <h2 class="text-lg font-semibold text-white pt-2">9. Rescisao</h2>
            <p>
                Podemos suspender ou encerrar sua conta a qualquer momento, sem aviso previo, caso violacoes
                destes termos sejam detectadas. Voce tambem pode solicitar a exclusão da sua conta a qualquer
                momento.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">10. Alteracoes nestes termos</h2>
            <p>
                Reservamo-nos o direito de alterar estes Termos de Uso a qualquer momento. Alteracoes significativas
                serao comunicadas atraves do servico ou por e-mail. O uso continuado do servico apos alteracoes
                constitui aceitacao dos novos termos.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">11. Legislacao aplicavel</h2>
            <p>
                Estes termos sao regidos pelas leis da Republica do Brasil. Qualquer disputa sera resolvida no
                foro da comarca de [Sua Cidade], com exclusao de quaisquer outros.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">12. Contato</h2>
            <p>
                Para esclarecer duvidas sobre estes Termos de Uso, entre em contato:
            </p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>E-mail: [seu-email@exemplo.com]</li>
            </ul>
        </div>
    </main>
    <x-footer />
</body>

</html>
