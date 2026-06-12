import 'vanilla-cookieconsent/dist/cookieconsent.css';
import * as CookieConsent from 'vanilla-cookieconsent';

window.CookieConsent = CookieConsent;

CookieConsent.run({
    cookie: {
        name: 'cc_cookie',
        expiresAfterDays: 182,
        sameSite: 'Lax',
    },

    mode: 'opt-in',

    categories: {
        necessary: {
            enabled: true,
            readOnly: true,
        },
        analytics: {
            autoClear: {
                cookies: [
                    { name: /^_ga/ },
                    { name: '_gid' },
                ],
            },
        },
    },

    guiOptions: {
        consentModal: {
            layout: 'box inline',
            position: 'bottom center',
            equalWeightButtons: true,
            flipButtons: false,
        },
        preferencesModal: {
            layout: 'box',
            equalWeightButtons: true,
            flipButtons: false,
        },
    },

    language: {
        default: 'pt',
        translations: {
            pt: {
                consentModal: {
                    title: 'Usamos cookies',
                    description: 'Este site utiliza cookies para garantir o funcionamento correto, melhorar sua experiencia e, opcionalmente, coletar estatisticas de uso de forma anonima. Voce pode gerenciar suas preferencias a qualquer momento.',
                    acceptAllBtn: 'Aceitar todos',
                    acceptNecessaryBtn: 'Apenas necessarios',
                    showPreferencesBtn: 'Gerenciar preferencias',
                    footer: `
                        <a href="/politica-de-privacidade" target="_blank">Privacidade</a>
                        <a href="/politica-de-cookies" target="_blank">Cookies</a>
                        <a href="/termos-de-uso" target="_blank">Termos</a>
                    `,
                },
                preferencesModal: {
                    title: 'Preferencias de cookies',
                    acceptAllBtn: 'Aceitar todos',
                    acceptNecessaryBtn: 'Apenas necessarios',
                    savePreferencesBtn: 'Salvar preferencias',
                    closeIconLabel: 'Fechar',
                    serviceCounterLabel: 'Servico|Servicos',
                    sections: [
                        {
                            title: 'Uso de cookies',
                            description: 'Utilizamos cookies para garantir o funcionamento correto do site e melhorar sua experiencia. Voce pode escolher quais categorias de cookies deseja permitir.',
                        },
                        {
                            title: 'Cookies Necessarios',
                            description: 'Essenciais para o funcionamento do site. Nao podem ser desativados.',
                            linkedCategory: 'necessary',
                        },
                        {
                            title: 'Cookies de Analise',
                            description: 'Nos ajudam a entender como voce utiliza o site, permitindo melhorar continuamente. Todas as informacoes sao coletadas de forma anonima.',
                            linkedCategory: 'analytics',
                        },
                        {
                            title: 'Mais informacoes',
                            description: 'Para qualquer duvida sobre nossa politica de cookies, consulte nossa <a href="/politica-de-cookies" target="_blank">Politica de Cookies</a> ou entre em contato.',
                        },
                    ],
                },
            },
        },
    },
});
