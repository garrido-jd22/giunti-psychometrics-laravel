<?php

it('starts with an empty cart', function () {
    $this->getJson(route('cart.show'))
        ->assertOk()
        ->assertJsonPath('count', 0)
        ->assertJsonPath('badge', '0')
        ->assertJsonPath('label', 'Carrello')
        ->assertJsonPath('total', "0,00\u{00A0}€")
        ->assertJson(fn ($json) => $json->where('html', fn (string $html): bool => str_contains($html, 'Il carrello è vuoto'))->etc());
});

it('renders the drawer lines and the accessible label after adding a product', function () {
    $response = $this->postJson(route('cart.items.store'), ['slug' => 'scala-umore'])->assertCreated();

    expect($response->json('label'))->toBe('Carrello, 1 prodotti')
        ->and($response->json('html'))->toContain('Scala dell&#039;Umore')
        ->toContain('data-cart-remove="scala-umore"')
        ->toContain("140,00\u{00A0}€");
});

it('caps the badge at 99+', function () {
    $this->withSession(['cart' => ['scala-umore' => 120]])
        ->getJson(route('cart.show'))
        ->assertJsonPath('badge', '99+');
});

it('adds a product and accumulates the quantity when added again', function () {
    $this->postJson(route('cart.items.store'), ['slug' => 'scala-umore'])
        ->assertCreated()
        ->assertJsonPath('count', 1);

    $this->postJson(route('cart.items.store'), ['slug' => 'scala-umore'])
        ->assertCreated()
        ->assertJsonPath('count', 2)
        ->assertJsonPath('total', "280,00\u{00A0}€");

    expect(session('cart'))->toBe(['scala-umore' => 2]);
});

it('sums the total of several products', function () {
    $this->postJson(route('cart.items.store'), ['slug' => 'scala-umore']);

    $this->postJson(route('cart.items.store'), ['slug' => 'test-abilita-cognitive'])
        ->assertJsonPath('count', 2)
        ->assertJsonPath('total', "680,00\u{00A0}€");
});

it('removes a product line from the cart', function () {
    $this->withSession(['cart' => ['scala-umore' => 3, 'profilo-competenze' => 1]])
        ->deleteJson(route('cart.items.destroy', 'scala-umore'))
        ->assertOk()
        ->assertJsonPath('count', 1);

    expect(session('cart'))->toBe(['profilo-competenze' => 1]);
});

it('ignores stored products that no longer exist in the catalog', function () {
    $this->withSession(['cart' => ['prodotto-rimosso' => 2, 'scala-umore' => 1]])
        ->getJson(route('cart.show'))
        ->assertJsonPath('count', 1);
});

it('returns 404 when adding a product that does not exist', function () {
    $this->postJson(route('cart.items.store'), ['slug' => 'prodotto-inesistente'])
        ->assertNotFound();
});

it('returns 422 when the slug is missing', function () {
    $this->postJson(route('cart.items.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('slug');
});
