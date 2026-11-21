<?php

it('finds products by title ignoring accents and case', function () {
    $this->getJson(route('products.search', ['q' => 'ABILITA']))
        ->assertOk()
        ->assertJsonPath('count', 1)
        ->assertJsonPath('results.0.slug', 'test-abilita-cognitive')
        ->assertJsonPath('results.0.url', route('products.show', 'test-abilita-cognitive'));
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
