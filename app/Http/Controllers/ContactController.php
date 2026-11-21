<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Recibe el formulario de contacto ya validado.
     */
    public function __invoke(ContactRequest $request, ContactService $contact): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        $contact->send($data);

        $message = 'Grazie '.$data['first_name'].', ti risponderemo entro 24 ore lavorative.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()
            ->to(route('home').'#'.config('contact.section_id'))
            ->with('contact_status', $message);
    }
}
