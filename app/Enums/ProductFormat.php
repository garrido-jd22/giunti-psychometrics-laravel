<?php

namespace App\Enums;

/**
 * Formato de un producto. El valor es también la etiqueta visible.
 */
enum ProductFormat: string
{
    case Cartaceo = 'Cartaceo';
    case Digitale = 'Digitale';
    case CorsoOnline = 'Corso online';
    case InPresenza = 'In presenza';

    public function label(): string
    {
        return $this->value;
    }
}
