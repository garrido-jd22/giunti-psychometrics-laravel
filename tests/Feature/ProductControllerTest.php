<?php

use App\DTOs\ProductData;
use App\Enums\ProductCategory;
use App\Enums\ProductFormat;
use App\Enums\ProductType;

describe('index', function () {
    it('falls back to the test tab when no type is given', function () {
        $this->get(route('products.index'))
            ->assertOk()
            ->assertViewIs('pages.products.index')
            ->assertViewHas('type', ProductType::Test)
            ->assertViewHas('totalCount', 5)
            ->assertSee('<title>Test · Catalogo Prodotti</title>', false);
    });

    it('falls back to the test tab when the type is not valid', function () {
        $this->get(route('products.index', ['type' => 'unknown']))
            ->assertOk()
            ->assertViewHas('type', ProductType::Test);
    });

    it('shows only courses on the formazione tab', function () {
        $this->get(route('products.index', ['type' => 'course']))
            ->assertOk()
            ->assertViewHas('type', ProductType::Course)
            ->assertViewHas('products', fn ($products): bool => $products->count() === 5
                && $products->every(fn (ProductData $product): bool => $product->type === ProductType::Course))
            ->assertSee('<title>Formazione · Catalogo Prodotti</title>', false);
    });

    it('passes the product count of every tab', function () {
        $this->get(route('products.index'))
            ->assertViewHas('counts', ['test' => 5, 'course' => 5]);
    });

    it('filters by category, format and search from the query string', function () {
        $response = $this->get(route('products.index', [
            'type' => 'test',
            'category' => ProductCategory::ClinicaNeuropsicologia->value,
            'format' => ProductFormat::Cartaceo->value,
            'search' => 'umore',
        ]));

        $response->assertOk()
            ->assertViewHas('products', fn ($products): bool => $products->pluck('slug')->all() === ['scala-umore'])
            ->assertViewHas('filters', fn ($filters): bool => $filters->hasActiveFilters());
    });

    it('ignores a format that does not belong to the selected tab', function () {
        $this->get(route('products.index', ['type' => 'test', 'format' => ProductFormat::InPresenza->value]))
            ->assertViewHas('filters', fn ($filters): bool => $filters->format === null)
            ->assertViewHas('products', fn ($products): bool => $products->count() === 5);
    });

    it('returns the filtered results as rendered html for ajax requests', function () {
        $response = $this->getJson(route('products.index', [
            'type' => 'course',
            'category' => ProductCategory::ScuolaEtaEvolutiva->value,
        ]));

        $response->assertOk()
            ->assertJsonPath('title', 'Formazione · Catalogo Prodotti')
            ->assertJsonPath('status', '2 di 5 corsi')
            ->assertJsonPath('hasActiveFilters', true);

        expect($response->json('results'))
            ->toContain('data-product-slug="corso-test-a-scuola"')
            ->toContain('data-product-slug="corso-sviluppo-infantile"')
            ->not->toContain('data-product-slug="corso-valutazione-clinica"')
            ->and($response->json('catalog'))->toContain('data-type="course"');
    });

    it('returns the empty state when no product matches', function () {
        $this->getJson(route('products.index', ['type' => 'test', 'search' => 'nessuna corrispondenza']))
            ->assertJsonPath('status', '0 di 5 test')
            ->assertJson(fn ($json) => $json->where('results', fn (string $html): bool => str_contains($html, 'Nessun prodotto corrisponde ai filtri selezionati.'))->etc());
    });
});

describe('show', function () {
    it('renders the product detail resolved by slug', function () {
        $this->get(route('products.show', 'scala-umore'))
            ->assertOk()
            ->assertViewIs('pages.products.show')
            ->assertViewHas('product', fn (ProductData $product): bool => $product->slug === 'scala-umore')
            ->assertViewHas('perks', config('products.perks.test'))
            ->assertSee('<title>Scala dell&#039;Umore</title>', false);
    });

    it('returns 404 with the product not found page when the slug does not exist', function () {
        $this->get(route('products.show', 'prodotto-inesistente'))
            ->assertNotFound()
            ->assertViewIs('pages.products.not-found')
            ->assertSee('Prodotto non trovato');
    });
});

it('renders the resources page', function () {
    $this->get(route('resources'))
        ->assertOk()
        ->assertViewIs('pages.resources')
        ->assertSee('Risorse');
});

it('returns 404 with the global error page for an unknown route', function () {
    $this->get('/pagina-inesistente')
        ->assertNotFound()
        ->assertSee('Ops! Non riusciamo a trovare questa pagina.');
});
