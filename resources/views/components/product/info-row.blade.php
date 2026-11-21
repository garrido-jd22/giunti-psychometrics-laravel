@props(['icon', 'label'])

<div class="info-row">
    <span class="info-row__icon">
        <x-ui.icon :name="$icon" />
    </span>
    <div class="info-row__text">
        <dt class="info-row__label">{{ $label }}</dt>
        <dd class="info-row__value">{{ $slot }}</dd>
    </div>
</div>
