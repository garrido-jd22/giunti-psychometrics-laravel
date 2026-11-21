<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Recepción del formulario de contacto: por ahora se registra en el log.
 * Sustituir por un Mailable o una API cuando exista el destino real.
 */
class ContactService
{
    /**
     * @param  array{first_name: string, last_name: ?string, email: string, message: string}  $message
     */
    public function send(array $message): void
    {
        Log::info('Contact message received', [
            'name' => trim($message['first_name'].' '.($message['last_name'] ?? '')),
            'email' => $message['email'],
        ]);
    }
}
