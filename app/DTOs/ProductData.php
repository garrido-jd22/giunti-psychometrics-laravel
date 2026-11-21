<?php

namespace App\DTOs;

use App\Enums\ProductActionType;
use App\Enums\ProductCategory;
use App\Enums\ProductFormat;
use App\Enums\ProductType;

/**
 * Producto del catálogo (test o curso).
 */
final readonly class ProductData
{
    public function __construct(
        public string $slug,
        public ProductType $type,
        public string $title,
        public string $description,
        public string $author,
        public string $authorRole,
        public ProductCategory $category,
        public ProductFormat $format,
        public ProductMeta $meta,
        public float $price,
        public ?float $originalPrice,
        public string $image,
        public ProductActionType $actionType,
        public bool $featured = false,
    ) {}

    /**
     * @param  array{slug: string, type: string, title: string, description: string, author: string, author_role: string, category: string, format: string, meta: array{type: string, label?: string, start_date?: string}, price: float|int, original_price?: float|int|null, image: string, action_type: string, featured?: bool}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            slug: $data['slug'],
            type: ProductType::from($data['type']),
            title: $data['title'],
            description: $data['description'],
            author: $data['author'],
            authorRole: $data['author_role'],
            category: ProductCategory::from($data['category']),
            format: ProductFormat::from($data['format']),
            meta: ProductMeta::fromArray($data['meta']),
            price: (float) $data['price'],
            originalPrice: isset($data['original_price']) ? (float) $data['original_price'] : null,
            image: $data['image'],
            actionType: ProductActionType::from($data['action_type']),
            featured: $data['featured'] ?? false,
        );
    }

    /**
     * URL del detalle: /products/{slug}.
     */
    public function url(): string
    {
        return route('products.show', $this->slug);
    }

    public function addsToCart(): bool
    {
        return $this->actionType === ProductActionType::Cart;
    }

    /**
     * Descuento en % (redondeado) o null si no hay un precio original mayor.
     */
    public function discountPercent(): ?int
    {
        if ($this->originalPrice === null || $this->originalPrice <= $this->price) {
            return null;
        }

        return (int) round((1 - $this->price / $this->originalPrice) * 100);
    }
}
