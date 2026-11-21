<?php

it('finds products by title ignoring accents and case', function () {
    $this->getJson(route('products.search', ['q' => 'ABILITA']))
        ->assertOk()
        ->assertJsonPath('count', 1)
        ->assertJsonPath('results.0.slug', 'test-abilita-cognitive')
        ->assertJsonPath('results.0.url', route('products.show', 'test-abilita-cognitive'));
});

it('renders the results list for the header popover', function () {
    $html = $this->getJson(route('products.search', ['q' => 'scala']))->json('html');

    expect($html)->toContain('href="'.route('products.show', 'scala-umore').'"')
        ->toContain('data-add-to-cart="scala-umore"');
});

it('renders the empty state when nothing matches', function () {
    expect($this->getJson(route('products.search', ['q' => 'zzz']))->json('html'))
        ->toContain('Nessun prodotto trovato.');
});

it('limits the results to the header limit', function () {
    config(['products.search.header_limit' => 2]);

    $this->getJson(route('products.search', ['q' => 'a']))
        ->assertOk()
        ->assertJsonCount(2, 'results');
});

it('does not search by description', function () {
    $this->getJson(route('products.search', ['q' => 'iperattività']))
        ->assertOk()
        ->assertJsonPath('count', 0);
});

it('returns no results for a blank query', function () {
    $this->getJson(route('products.search', ['q' => '   ']))
        ->assertOk()
        ->assertJsonPath('count', 0)
        ->assertJsonPath('results', []);
});
