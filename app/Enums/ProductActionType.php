<?php

namespace App\Enums;

/**
 * Acción principal de la card: añadir al carrito o ir al detalle.
 */
enum ProductActionType: string
{
    case Cart = 'cart';
    case Details = 'details';
}
