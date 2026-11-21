<?php

namespace App\Support;

/**
 * Resuelve los enlaces definidos en config/site.php.
 */
final class Link
{
    /**
     * @param  array{url?: string, route?: string, params?: array<string, string>, fragment?: string}  $link
     */
    public static function url(array $link): string
    {
        $url = isset($link['route']) ? route($link['route'], $link['params'] ?? []) : ($link['url'] ?? '#');

        return isset($link['fragment']) ? $url.'#'.$link['fragment'] : $url;
    }
}
