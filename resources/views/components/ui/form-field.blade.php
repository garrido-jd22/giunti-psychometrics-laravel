{{-- Etiqueta + control (slot) + mensaje de error o pista. --}}
@props([
    'id',
    'label',
    'error' => null,
    'hint' => null,
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'form-field']) }} data-form-field="{{ $id }}">
    {{-- Sin espacios entre el texto y el marcador (el margen lo da el CSS) --}}
    <label for="{{ $id }}" class="form-field__label">{{ $label }}@if ($required)<span class="form-field__required" aria-hidden="true">*</span>@else<span class="form-field__optional">(facoltativo)</span>@endif</label>

    {{ $slot }}

    <p id="{{ $id }}-error" class="form-field__error" @if (! $error) hidden @endif data-field-error>
        <x-ui.icon name="circle-alert" class="form-field__error-icon" />
        <span data-field-error-text>{{ $error }}</span>
    </p>

    @if ($hint !== null)
        <p id="{{ $id }}-hint" class="form-field__hint" @if ($error) hidden @endif data-field-hint>{{ $hint }}</p>
    @endif
</div>
