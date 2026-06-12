<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Politica de Privacidade | {{ config('app.name', 'Laravel') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
    @endif
</head>

<body class="bg-[#0a0a0a] text-white flex flex-col min-h-screen">
    <x-header />
    <main class="w-full max-w-100 sm:max-w-200 lg:w-150 mx-auto flex-1 flex flex-col gap-6 py-8 px-4 sm:px-6">
        <h1 class="text-2xl sm:text-3xl font-semibold">Politica de Privacidade</h1>

        <div class="flex flex-col gap-4 text-white/70 text-sm leading-relaxed">
            <p><strong class="text-white">Ultima atualizacao:</strong> 12 de junho de 2026</p>

            <h2 class="text-lg font-semibold text-white pt-2">1. Quem somos</h2>
            <p>
                O <strong class="text-white">encurta.ai</strong> e um servico de encurtamento de URLs operado por
                [Seu Nome/Empresa].
                Esta Politica de Privacidade descreve como coletamos, usamos e protegemos seus dados pessoais em
                conformidade com a <strong class="text-white">Lei Geral de Protecao de Dados (LGPD - Lei n. 13.709/2018)</strong>.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">2. Dados que coletamos</h2>
            <p>Coletamos os seguintes dados pessoais:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li><strong class="text-white">Dados de autenticacao:</strong> nome, endereco de e-mail e foto de perfil,
                    fornecidos via autenticacao do Google (OAuth).</li>
                <li><strong class="text-white">Dados de navegacao:</strong> endereco IP (hash SHA-256), user-agent e
                    timestamps de acesso.</li>
                <li><strong class="text-white">Dados de uso:</strong> URLs encurtadas, cliques realizados e metadados
                    associados.</li>
                <li><strong class="text-white">Cookies:</strong> cookies de sessao essenciais e cookies de preferencias
                    de consentimento.</li>
            </ul>

            <h2 class="text-lg font-semibold text-white pt-2">3. Finalidade do tratamento</h2>
            <p>Seus dados sao utilizados para:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>Operacao e manutencao do servico de encurtamento de URLs.</li>
                <li>Autenticacao e gerenciamento da sua conta.</li>
                <li>Registro de estatisticas de cliques nos links encurtados.</li>
                <li>Seguranca e prevencao de abusos.</li>
                <li>Cumprimento de obrigacoes legais.</li>
            </ul>

            <h2 class="text-lg font-semibold text-white pt-2">4. Compartilhamento de dados</h2>
            <p>
                Seus dados podem ser compartilhados com:
            </p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li><strong class="text-white">Google LLC:</strong> para autenticacao via OAuth (nome, e-mail, foto).
                    Consulte a
                    <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer"
                        class="text-white underline hover:text-white/80">Politica de Privacidade do Google</a>.</li>
            </ul>
            <p>Nao vendemos ou compartilhamos dados pessoais com terceiros para fins de marketing.</p>

            <h2 class="text-lg font-semibold text-white pt-2">5. Seguranca dos dados</h2>
            <p>
                Adotamos medidas tecnicas e administrativas para proteger seus dados, incluindo criptografia de senhas,
                hash de enderecos IP, sessoes criptografadas e acesso restrito a dados pessoais.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">6. Seus direitos (LGPD)</h2>
            <p>Conforme a LGPD, voce tem direito a:</p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li><strong class="text-white">Confirmacao</strong> da existencia de tratamento de dados.</li>
                <li><strong class="text-white">Acesso</strong> aos seus dados pessoais.</li>
                <li><strong class="text-white">Correcao</strong> de dados incompletos ou desatualizados.</li>
                <li><strong class="text-white">Anonimizacao, bloqueio ou eliminacao</strong> de dados desnecessarios.</li>
                <li><strong class="text-white">Portabilidade</strong> dos dados.</li>
                <li><strong class="text-white">Eliminacao</strong> dos dados tratados com consentimento.</li>
                <li><strong class="text-white">Informacao</strong> sobre compartilhamento de dados.</li>
                <li><strong class="text-white">Revogacao do consentimento</strong>, a qualquer momento.</li>
            </ul>

            <h2 class="text-lg font-semibold text-white pt-2">7. Retencao de dados</h2>
            <p>
                Seus dados sao mantidos pelo periodo necessario para a prestacao do servico e cumprimento de
                obrigacoes legais. Contas inativas por mais de 2 anos podem ser removidas.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">8. Cookies</h2>
            <p>
                Utilizamos cookies para o funcionamento do site e, opcionalmente, para analise de uso. Consulte nossa
                <a href="{{ route('legal.cookies') }}" class="text-white underline hover:text-white/80">Politica de
                    Cookies</a> para mais detalhes.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">9. Termos de Uso</h2>
            <p>
                O uso do encurta.ai tambem e regido por nossos
                <a href="{{ route('legal.terms') }}" class="text-white underline hover:text-white/80">Termos de
                    Uso</a>.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">10. Alteracoes nesta politica</h2>
            <p>
                Podemos atualizar esta Politica de Privacidade periodicamente. Notificaremos sobre mudancas
                significativas atraves do servico ou por e-mail.
            </p>

            <h2 class="text-lg font-semibold text-white pt-2">11. Contato</h2>
            <p>
                Para exercer seus direitos ou esclarecer duvidas, entre em contato:
            </p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li>E-mail: [seu-email@exemplo.com]</li>
            </ul>
        </div>
    </main>
    <x-footer />
</body>

</html>
