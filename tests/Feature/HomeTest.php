<?php

use App\DTOs\ProductData;

it('renders the home page with only the featured products', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertViewIs('pages.home')
        ->assertViewHas('featuredProducts', function ($products): bool {
            return $products->count() === 6
                && $products->every(fn (ProductData $product): bool => $product->featured);
        });
});

it('renders the contact section anchor used by the navigation', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('id="contatti"', false);
});

it('renders every navigation link from the site configuration', function () {
    $response = $this->get(route('home'));

    $response->assertSee('href="'.route('products.index', ['type' => 'test']).'"', false)
        ->assertSee('href="'.route('products.index', ['type' => 'course']).'"', false)
        ->assertSee('href="'.route('resources').'"', false)
        ->assertSee('href="'.route('home').'#contatti"', false);
});

it('uses the original site title', function () {
    $this->get(route('home'))->assertSee('<title>Giunti Psychometrics Italy</title>', false);
});
