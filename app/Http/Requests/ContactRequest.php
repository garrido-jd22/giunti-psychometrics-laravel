<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación del formulario de contacto (mismas reglas y mensajes que el cliente).
 */
class ContactRequest extends FormRequest
{
    /**
     * Algo@algo.dominio (dominio de al menos 2 caracteres), sin espacios.
     */
    public const EMAIL_PATTERN = '/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:'.config('contact.rules.first_name_min_length'), 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'regex:'.self::EMAIL_PATTERN],
            'message' => ['required', 'string', 'min:'.config('contact.rules.message_min_length'), 'max:5000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $firstNameMin = config('contact.rules.first_name_min_length');
        $messageMin = config('contact.rules.message_min_length');

        return [
            'first_name.required' => 'Il nome è obbligatorio.',
            'first_name.min' => "Il nome deve contenere almeno {$firstNameMin} caratteri.",
            'first_name.max' => 'Il nome non può superare i 255 caratteri.',
            'last_name.max' => 'Il cognome non può superare i 255 caratteri.',
            'email.required' => "L'email è obbligatoria.",
            'email.regex' => 'Inserisci un indirizzo email valido (es. nome@dominio.it).',
            'email.max' => "L'email non può superare i 255 caratteri.",
            'message.required' => 'Il messaggio è obbligatorio.',
            'message.min' => "Il messaggio deve contenere almeno {$messageMin} caratteri.",
            'message.max' => 'Il messaggio non può superare i 5000 caratteri.',
        ];
    }

    /**
     * Sin JavaScript, los errores vuelven a la sección de contacto de la home.
     */
    protected function getRedirectUrl(): string
    {
        return route('home').'#'.config('contact.section_id');
    }
}
