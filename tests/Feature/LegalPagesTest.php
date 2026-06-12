<?php

test('privacy policy page returns 200', function () {
    $response = $this->get(route('legal.privacy'));

    $response->assertStatus(200);
    $response->assertSee('Politica de Privacidade');
});

test('cookie policy page returns 200', function () {
    $response = $this->get(route('legal.cookies'));

    $response->assertStatus(200);
    $response->assertSee('Politica de Cookies');
});

test('terms of use page returns 200', function () {
    $response = $this->get(route('legal.terms'));

    $response->assertStatus(200);
    $response->assertSee('Termos de Uso');
});

test('home page has footer with legal links', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Privacidade');
    $response->assertSee('Cookies');
    $response->assertSee('Termos');
    $response->assertSee('Gerenciar cookies');
});

test('privacy page has lgpd content', function () {
    $response = $this->get(route('legal.privacy'));

    $response->assertSee('LGPD');
    $response->assertSee('Lei Geral de Protecao de Dados');
    $response->assertSee('direitos');
});

test('cookie page has cookie categories', function () {
    $response = $this->get(route('legal.cookies'));

    $response->assertSee('Necessarios');
    $response->assertSee('Analise');
    $response->assertSee('shortlink-session');
    $response->assertSee('cc_cookie');
});

test('footer links work correctly', function () {
    $privacy = $this->get(route('legal.privacy'));
    $privacy->assertStatus(200);

    $cookies = $this->get(route('legal.cookies'));
    $cookies->assertStatus(200);

    $terms = $this->get(route('legal.terms'));
    $terms->assertStatus(200);
});

test('terms page has content about acceptable use', function () {
    $response = $this->get(route('legal.terms'));

    $response->assertSee('Aceitacao dos termos');
    $response->assertSee('Uso aceitavel');
    $response->assertSee('Propriedade intelectual');
    $response->assertSee('Legislacao aplicavel');
});
