<?php

namespace App\Enums;

/**
 * Área de aplicación de un producto. El valor es también la etiqueta visible.
 */
enum ProductCategory: string
{
    case ClinicaNeuropsicologia = 'Clinica e Neuropsicologia';
    case ScuolaEtaEvolutiva = 'Scuola ed Età evolutiva';
    case LavoroOrganizzazioni = 'Lavoro e Organizzazioni';

    public function label(): string
    {
        return $this->value;
    }

    /**
     * Opciones del select y de los chips de categoría ("all" = sin filtro).
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = ['all' => 'Tutti'];

        foreach (self::cases() as $category) {
            $options[$category->value] = $category->label();
        }

        return $options;
    }
}
