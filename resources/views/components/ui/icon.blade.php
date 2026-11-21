{{-- SVG de resources/views/icons/{name}. Decorativo (aria-hidden) salvo que se pase aria-label. --}}
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
    {{ $attributes->merge(['class' => 'icon', 'aria-hidden' => 'true', 'focusable' => 'false']) }}
>
    @include('icons.'.$name)
</svg>
