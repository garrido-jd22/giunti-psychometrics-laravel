<?php

namespace App\DTOs;

use App\Enums\ProductMetaType;
use Carbon\CarbonImmutable;

/**
 * Rango de edad (tests) o fecha de inicio (cursos).
 */
final readonly class ProductMeta
{
    public function __construct(
        public ProductMetaType $type,
        public ?string $label = null,
        public ?CarbonImmutable $startDate = null,
    ) {}

    /**
     * @param  array{type: string, label?: string, start_date?: string}  $data
     */
    public static function fromArray(array $data): self
    {
        $type = ProductMetaType::from($data['type']);

        return match ($type) {
            ProductMetaType::Age => new self($type, label: $data['label']),
            ProductMetaType::Date => new self($type, startDate: CarbonImmutable::parse($data['start_date'], 'UTC')),
        };
    }

    public function isAge(): bool
    {
        return $this->type === ProductMetaType::Age;
    }
}
