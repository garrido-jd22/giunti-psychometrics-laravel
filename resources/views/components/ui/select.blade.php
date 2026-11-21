{{-- Listbox WAI-ARIA; el valor va en un input oculto para enviarlo con el formulario. --}}
@props([
    'name',
    'label',
    'options',
    'value',
    'icon' => null,
    'variant' => 'default',
])

@php($listboxId = 'select-'.$name)

<div {{ $attributes->merge(['class' => "select select--{$variant}"]) }} data-select>
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" data-select-input>

    <button
        type="button"
        class="select__trigger"
        role="combobox"
        aria-label="{{ $label }}"
        aria-haspopup="listbox"
        aria-expanded="false"
        aria-controls="{{ $listboxId }}"
        data-select-trigger
    >
        @if ($icon)
            <x-ui.icon :name="$icon" class="select__icon" />
        @endif
        <span class="select__value" data-select-value>{{ $options[$value] ?? $label }}</span>
        <x-ui.icon name="chevron-down" class="select__chevron" />
    </button>

    <ul id="{{ $listboxId }}" class="select__listbox" role="listbox" aria-label="{{ $label }}" hidden data-select-listbox>
        @foreach ($options as $optionValue => $optionLabel)
            @php($isSelected = (string) $optionValue === (string) $value)
            <li
                id="{{ $listboxId }}-option-{{ $loop->index }}"
                role="option"
                aria-selected="{{ $isSelected ? 'true' : 'false' }}"
                @class(['select__option', 'select__option--selected' => $isSelected])
                data-value="{{ $optionValue }}"
            >
                <span>{{ $optionLabel }}</span>
                <x-ui.icon name="check" class="select__check" :stroke-width="2.4" />
            </li>
        @endforeach
    </ul>
</div>
