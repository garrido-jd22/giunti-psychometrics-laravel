import $ from 'jquery';
import { withMinimumDelay } from '../utils/http';
import { toast } from './toast';

/** Orden en que se enfoca el primer campo inválido al enviar. */
const FIELD_ORDER = ['first_name', 'last_name', 'email', 'message'];

/** Algo@algo.dominio (dominio de al menos 2 caracteres), sin espacios. Igual que ContactRequest. */
const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

const INVALID_TOAST = ['Controlla i campi del modulo', { description: 'Alcuni campi obbligatori sono vuoti o non validi.' }];
const ERROR_TOAST = ['Invio non riuscito', { description: 'Si è verificato un problema. Riprova tra qualche istante.' }];

/**
 * Validación del cliente (mismas reglas y mensajes que App\Http\Requests\ContactRequest).
 *
 * @returns {Record<string, string>} Solo los campos con error.
 */
function validate(values, rules) {
    const errors = {};
    const firstName = values.first_name.trim();
    const email = values.email.trim();
    const message = values.message.trim();

    if (!firstName) {
        errors.first_name = 'Il nome è obbligatorio.';
    } else if (firstName.length < rules.firstNameMin) {
        errors.first_name = `Il nome deve contenere almeno ${rules.firstNameMin} caratteri.`;
    }

    if (!email) {
        errors.email = "L'email è obbligatoria.";
    } else if (!EMAIL_PATTERN.test(email)) {
        errors.email = 'Inserisci un indirizzo email valido (es. nome@dominio.it).';
    }

    if (!message) {
        errors.message = 'Il messaggio è obbligatorio.';
    } else if (message.length < rules.messageMin) {
        errors.message = `Il messaggio deve contenere almeno ${rules.messageMin} caratteri.`;
    }

    return errors;
}

/**
 * Formulario de contacto: errores al salir del campo o al enviar, contador de
 * caracteres, estados idle / enviando / enviado y envío por AJAX.
 */
export function initContactForm() {
    const $form = $('[data-contact-form]');

    if (!$form.length) {
        return;
    }

    const rules = { firstNameMin: Number($form.data('firstNameMin')), messageMin: Number($form.data('messageMin')) };
    const submitDelay = Number($form.data('submitDelay')) || 0;
    const $submit = $form.find('[data-contact-submit]');
    const $submitLabel = $form.find('[data-contact-submit-label]');
    const $success = $form.find('[data-contact-success]');
    const $note = $form.find('[data-contact-note]');
    const $control = (name) => $form.find(`[name="${name}"]`);

    let touched = {};
    let serverErrors = {};
    let submitAttempted = $form.find('[data-field-error]:not([hidden])').length > 0;
    let status = $success.prop('hidden') ? 'idle' : 'success';

    const values = () => Object.fromEntries(FIELD_ORDER.map((name) => [name, String($control(name).val() ?? '')]));

    const visibleError = (name, errors) => (touched[name] || submitAttempted ? errors[name] ?? serverErrors[name] : undefined);

    const render = () => {
        const current = values();
        const errors = validate(current, rules);

        FIELD_ORDER.forEach((name) => {
            const $field = $form.find(`[data-form-field="${name}"]`);
            const $input = $control(name);
            const error = visibleError(name, errors);
            const $hint = $field.find('[data-field-hint]');

            $field.find('[data-field-error]').prop('hidden', !error);
            $field.find('[data-field-error-text]').text(error ?? '');
            $hint.prop('hidden', Boolean(error));
            $input.toggleClass('field-control--error', Boolean(error));
            error ? $input.attr('aria-invalid', 'true') : $input.removeAttr('aria-invalid');

            const describedBy = error ? `${name}-error` : $hint.length ? `${name}-hint` : null;
            describedBy ? $input.attr('aria-describedby', describedBy) : $input.removeAttr('aria-describedby');
        });

        $form.find('[data-form-field="message"] [data-field-hint]')
            .text(`Minimo ${rules.messageMin} caratteri · ${current.message.trim().length} scritti`);

        const isSubmitting = status === 'submitting';
        const isSuccess = status === 'success';

        $submit
            .prop('disabled', isSubmitting)
            .attr('aria-busy', String(isSubmitting))
            .toggleClass('contact-form__submit--loading', isSubmitting)
            .toggleClass('contact-form__submit--success', isSuccess);
        $submitLabel.text(isSubmitting ? 'Invio in corso...' : isSuccess ? 'Inviato' : 'Invia Messaggio');
        $success.prop('hidden', !isSuccess);
        $note.prop('hidden', isSuccess);
    };

    const focusFirstInvalid = (errors) => {
        const first = FIELD_ORDER.find((name) => errors[name]);
        if (first) {
            $control(first).trigger('focus');
        }
    };

    $form.on('input', 'input, textarea', function () {
        delete serverErrors[this.name];

        // Si el usuario vuelve a escribir tras enviar, se quita el estado de éxito.
        if (status === 'success') {
            status = 'idle';
        }

        render();
    });

    $form.on('focusout', 'input, textarea', function () {
        touched[this.name] = true;
        render();
    });

    $form.on('submit', (event) => {
        event.preventDefault();

        if (status === 'submitting') {
            return;
        }

        submitAttempted = true;
        const errors = validate(values(), rules);

        if (Object.keys(errors).length > 0) {
            render();
            focusFirstInvalid(errors);
            toast.warning(...INVALID_TOAST);
            return;
        }

        status = 'submitting';
        render();

        const request = $.ajax({ url: $form.attr('action'), method: 'POST', data: $form.serialize() });

        withMinimumDelay(request, submitDelay)
            .then((response) => {
                status = 'success';
                toast.success('Messaggio inviato', { description: response.message });
                FIELD_ORDER.forEach((name) => $control(name).val(''));
                touched = {};
                serverErrors = {};
                submitAttempted = false;
                render();
            })
            .catch((xhr) => {
                status = 'idle';

                if (xhr?.status === 422) {
                    serverErrors = Object.fromEntries(Object.entries(xhr.responseJSON?.errors ?? {}).map(([name, messages]) => [name, messages[0]]));
                    render();
                    focusFirstInvalid(serverErrors);
                    toast.warning(...INVALID_TOAST);
                    return;
                }

                render();
                toast.error(...ERROR_TOAST);
            });
    });

    render();
}
