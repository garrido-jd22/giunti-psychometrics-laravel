@extends('layouts.page')

@section('page-title', 'Risorse')
@section('page-subtitle', 'Risorse: libri, materiali, ecc.')

@section('page-content')
    <section class="resources">
        <div class="resources__glow" aria-hidden="true"></div>

        <div class="resources__card">
            <div class="resources__badge">
                <x-ui.icon name="sparkles" />
                <span>Pagina in corso di realizzazione</span>
            </div>

            <h1 class="resources__title">
                Stiamo perfezionando
                <span class="resources__highlight">la nuova esperienza</span>
            </h1>

            <p class="resources__text">
                Il nostro team sta lavorando per completare questa sezione. Torna a
                visitarci a breve per scoprire tutte le novità e gli strumenti
                disponibili.
            </p>

            <div class="resources__progress">
                <div class="resources__progress-labels">
                    <span>Avanzamento</span>
                    <span>75%</span>
                </div>
                <div class="resources__progress-track">
                    <div class="resources__progress-bar"></div>
                </div>
            </div>

            <div class="resources__actions">
                <a href="{{ route('home') }}" class="button button--primary button--arrow resources__home">
                    <span>Torna alla home</span>
                    <span class="button__arrow">
                        <x-ui.icon name="arrow-right" />
                    </span>
                </a>

                {{-- Igual que en el original: ancla en la propia página. --}}
                <a href="#contatti" class="resources__contact">Contattaci</a>
            </div>
        </div>
    </section>
@endsection
