{{-- 404 global: sin header ni footer, como app/not-found.tsx del original. --}}
@extends('layouts.app')

@section('bare', true)

@section('content')
    <div class="error-page">
        <div class="error-page__container">
            <div class="error-page__content">
                <div class="error-page__code">404</div>
                <p class="error-page__title">Ops! Non riusciamo a trovare questa pagina. </p>
                <p class="error-page__text">Ma possiamo aiutarti a trovare quello che stavi cercando</p>

                <a href="{{ route('home') }}" class="error-page__button">Torna alla pagina principale</a>
            </div>
        </div>
    </div>
@endsection
