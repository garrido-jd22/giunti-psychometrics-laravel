{{-- Contenedor de notificaciones. resources/js/modules/toast.js clona las plantillas. --}}
<div class="toaster" aria-live="polite" aria-label="Notifiche" data-toaster></div>

<template data-toast-template>
    <div class="toast" role="status">
        <span class="toast__icon" data-toast-icon></span>
        <div class="toast__body">
            <p class="toast__title" data-toast-title></p>
            <p class="toast__description" data-toast-description></p>
        </div>
        <button type="button" class="toast__close" aria-label="Chiudi notifica" data-toast-close>
            <x-ui.icon name="x" />
        </button>
        <span class="toast__progress" aria-hidden="true" data-toast-progress></span>
    </div>
</template>

@foreach (['success' => 'circle-check', 'error' => 'circle-x', 'warning' => 'triangle-alert', 'info' => 'info', 'primary' => 'sparkles', 'secondary' => 'bell'] as $variant => $icon)
    <template data-toast-icon-template="{{ $variant }}">
        <x-ui.icon :name="$icon" />
    </template>
@endforeach
