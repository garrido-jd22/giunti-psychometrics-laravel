<?php

use App\DTOs\ProductFilters;
use App\Enums\ProductCategory;
use App\Enums\ProductFormat;
use App\Enums\ProductType;
use App\Services\ProductService;

beforeEach(function () {
    $this->service = app(ProductService::class);
});

it('keeps the catalog order of the sample data', function () {
    expect($this->service->all()->pluck('slug')->first())->toBe('test-personalita-adulti')
        ->and($this->service->all())->toHaveCount(10);
});

it('finds a product by slug and returns null for an unknown slug', function () {
    expect($this->service->findBySlug('scala-umore')?->title)->toBe("Scala dell'Umore")
        ->and($this->service->findBySlug('nope'))->toBeNull();
});

it('searches the catalog by title, description and author ignoring accents', function () {
    $tests = $this->service->byType(ProductType::Test);

    expect($this->service->filter($tests, new ProductFilters(search: 'eta evolutiva'))->pluck('slug')->all())
        ->toBe(['test-abilita-cognitive', 'questionario-attenzione'])
        ->and($this->service->filter($tests, new ProductFilters(search: 'IPERATTIVITA'))->pluck('slug')->all())
        ->toBe(['questionario-attenzione']);
});

it('combines category and format filters', function () {
    $tests = $this->service->byType(ProductType::Test);

    $result = $this->service->filter($tests, new ProductFilters(
        category: ProductCategory::ScuolaEtaEvolutiva,
        format: ProductFormat::Digitale,
    ));

    expect($result->pluck('slug')->all())->toBe(['questionario-attenzione']);
});

it('returns every product when no filter is active', function () {
    $courses = $this->service->byType(ProductType::Course);

    expect($this->service->filter($courses, new ProductFilters))->toHaveCount(5);
});

it('computes the rounded discount only when there is a higher original price', function () {
    expect($this->service->findBySlug('test-personalita-adulti')->discountPercent())->toBe(16)
        ->and($this->service->findBySlug('scala-umore')->discountPercent())->toBeNull();
});
