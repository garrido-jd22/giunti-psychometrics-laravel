<?php

namespace App\Enums;

/**
 * Dato secundario del producto: rango de edad (tests) o fecha de inicio (cursos).
 */
enum ProductMetaType: string
{
    case Age = 'age';
    case Date = 'date';
}
