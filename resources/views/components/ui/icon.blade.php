{{--
    Icono SVG (trazos de lucide, licencia ISC) guardado en resources/views/icons/{name}.blade.php.
    Uso: <x-ui.icon name="search" class="header__search-icon" :stroke-width="1.8" />
    Decorativo por defecto (aria-hidden); pasa aria-label y role="img" si transmite información.
--}}
@props([
    'name',
    'strokeWidth' => 2,
])

<svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="{{ $strokeWidth }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    {{ $attributes->merge(['class' => 'icon icon--'.$name, 'aria-hidden' => 'true', 'focusable' => 'false']) }}
>
    @include('icons.'.$name)
</svg>
