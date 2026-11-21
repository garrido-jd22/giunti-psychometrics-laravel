{{--
    Contacto. El formulario funciona sin JavaScript (POST + validación de Laravel);
    con JS se valida en el cliente y se envía por AJAX (Etapa 5).
--}}
@php
    $contact = config('contact');
    $messageMin = $contact['rules']['message_min_length'];
    $status = session('contact_status');
@endphp

<section id="{{ $contact['section_id'] }}" class="contact">
    <div class="container contact__grid">
        <div class="contact__intro u-animate-fade-up">
            <h2 class="contact__title">
                Mettiti in <span class="contact__dash">—</span>
                <br>
                contatto con noi
            </h2>

            <p class="contact__text">
                Siamo qui per aiutarti! Che tu abbia una domanda sui nostri test
                psicometrici, necessiti di assistenza con il tuo account o voglia
                maggiori informazioni sui corsi, il nostro team è pronto a
                supportarti.
            </p>

            <div class="contact__details">
                <div>
                    <span class="contact__label">Email:</span>
                    <a href="mailto:{{ $contact['email'] }}" class="contact__value">{{ $contact['email'] }}</a>
                </div>

                <div>
                    <span class="contact__label">Telefono:</span>
                    <a href="{{ $contact['phone']['href'] }}" class="contact__value contact__value--block">{{ $contact['phone']['label'] }}</a>
                    <span class="contact__hours">{{ $contact['phone']['hours'] }}</span>
                </div>
            </div>
        </div>

        <div class="contact__panel-wrapper u-animate-fade-up">
            <div class="contact__panel">
                <div class="contact__glow" aria-hidden="true"></div>

                <form
                    method="POST"
                    action="{{ route('contact.store') }}"
                    class="contact-form"
                    novalidate
                    data-contact-form
                    data-first-name-min="{{ $contact['rules']['first_name_min_length'] }}"
                    data-message-min="{{ $messageMin }}"
                    data-submit-delay="{{ $contact['submit_delay_ms'] }}"
                >
                    @csrf

                    <div class="contact-form__row">
                        <x-ui.form-field id="first_name" label="Nome" required :error="$errors->first('first_name')">
                            <input
                                id="first_name"
                                name="first_name"
                                type="text"
                                autocomplete="given-name"
                                value="{{ old('first_name') }}"
                                aria-required="true"
                                @if ($errors->has('first_name')) aria-invalid="true" aria-describedby="first_name-error" @endif
                                placeholder="Inserisci il tuo nome..."
                                @class(['field-control', 'field-control--error' => $errors->has('first_name')])
                            >
                        </x-ui.form-field>

                        <x-ui.form-field id="last_name" label="Cognome">
                            <input
                                id="last_name"
                                name="last_name"
                                type="text"
                                autocomplete="family-name"
                                value="{{ old('last_name') }}"
                                placeholder="Inserisci il tuo cognome..."
                                class="field-control"
                            >
                        </x-ui.form-field>
                    </div>

                    <x-ui.form-field id="email" label="Email" required :error="$errors->first('email')">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            inputmode="email"
                            autocomplete="email"
                            value="{{ old('email') }}"
                            aria-required="true"
                            @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif
                            placeholder="Inserisci il tuo indirizzo email..."
                            @class(['field-control', 'field-control--error' => $errors->has('email')])
                        >
                    </x-ui.form-field>

                    <x-ui.form-field
                        id="message"
                        label="Come possiamo aiutarti?"
                        required
                        :error="$errors->first('message')"
                        :hint="'Minimo '.$messageMin.' caratteri · '.mb_strlen(trim((string) old('message'))).' scritti'"
                    >
                        <textarea
                            id="message"
                            name="message"
                            rows="5"
                            aria-required="true"
                            aria-describedby="{{ $errors->has('message') ? 'message-error' : 'message-hint' }}"
                            @if ($errors->has('message')) aria-invalid="true" @endif
                            placeholder="Scrivi qui il tuo messaggio..."
                            @class(['field-control', 'field-control--textarea', 'field-control--error' => $errors->has('message')])
                        >{{ old('message') }}</textarea>
                    </x-ui.form-field>

                    <div class="contact-form__footer">
                        <div class="contact-form__success" role="status" @unless ($status) hidden @endunless data-contact-success>
                            <x-ui.icon name="circle-check" />
                            <span>Messaggio inviato correttamente. Ti risponderemo presto.</span>
                        </div>
                        <span class="contact-form__note" @if ($status) hidden @endif data-contact-note>
                            Risposta garantita entro 24 ore lavorative.
                        </span>

                        <button
                            type="submit"
                            @class(['contact-form__submit', 'contact-form__submit--success' => $status])
                            data-contact-submit
                        >
                            <span data-contact-submit-label>{{ $status ? 'Inviato' : 'Invia Messaggio' }}</span>
                            <span class="contact-form__submit-icon">
                                <x-ui.icon name="arrow-right" class="contact-form__state-icon contact-form__state-icon--idle" />
                                <x-ui.icon name="loader-circle" class="contact-form__state-icon contact-form__state-icon--loading u-spin" />
                                <x-ui.icon name="check" class="contact-form__state-icon contact-form__state-icon--success" />
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
