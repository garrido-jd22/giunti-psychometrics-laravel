<?php

use Illuminate\Support\Facades\Log;

function validContactMessage(array $overrides = []): array
{
    return array_merge([
        'first_name' => 'Giulia',
        'last_name' => '',
        'email' => 'giulia@dominio.it',
        'message' => 'Vorrei informazioni sui corsi.',
    ], $overrides);
}

it('accepts a valid message and answers with the confirmation text', function () {
    Log::spy();

    $this->postJson(route('contact.store'), validContactMessage())
        ->assertOk()
        ->assertJsonPath('message', 'Grazie Giulia, ti risponderemo entro 24 ore lavorative.');

    Log::shouldHaveReceived('info')->once()->withArgs(
        fn (string $message, array $context): bool => $context['email'] === 'giulia@dominio.it',
    );
});

it('returns 422 with every required field error for an empty form', function () {
    $this->postJson(route('contact.store'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'first_name' => 'Il nome è obbligatorio.',
            'email' => "L'email è obbligatoria.",
            'message' => 'Il messaggio è obbligatorio.',
        ])
        ->assertJsonMissingValidationErrors('last_name');
});

it('returns 422 when the name is shorter than the minimum', function () {
    $this->postJson(route('contact.store'), validContactMessage(['first_name' => 'G']))
        ->assertJsonValidationErrors(['first_name' => 'Il nome deve contenere almeno 2 caratteri.']);
});

it('returns 422 when the email does not match the original pattern', function (string $email) {
    $this->postJson(route('contact.store'), validContactMessage(['email' => $email]))
        ->assertJsonValidationErrors(['email' => 'Inserisci un indirizzo email valido (es. nome@dominio.it).']);
})->with(['senza-chiocciola.it', 'nome@dominio', 'nome@dominio.i', 'no me@dominio.it']);

it('returns 422 when the message is shorter than the minimum', function () {
    $this->postJson(route('contact.store'), validContactMessage(['message' => 'Corto']))
        ->assertJsonValidationErrors(['message' => 'Il messaggio deve contenere almeno 10 caratteri.']);
});

it('redirects back to the contact section without javascript', function () {
    $this->post(route('contact.store'), validContactMessage())
        ->assertRedirect(route('home').'#contatti')
        ->assertSessionHas('contact_status');
});

it('redirects invalid submissions to the contact section with errors', function () {
    $this->post(route('contact.store'), [])
        ->assertRedirect(route('home').'#contatti')
        ->assertSessionHasErrors(['first_name', 'email', 'message']);
});
