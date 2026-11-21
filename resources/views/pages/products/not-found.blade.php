@extends('layouts.app')

@section('title', 'Prodotto non trovato')

@section('content')
    <section class="not-found">
        <x-ui.icon name="search-x" class="not-found__icon" :stroke-width="1.6" />
        <h1 class="not-found__title">Prodotto non trovato</h1>
        <p class="not-found__text">Il prodotto che cerchi non esiste o non è più disponibile.</p>
        <a href="{{ route('products.index') }}" class="button button--primary not-found__button">Torna al catalogo</a>
    </section>
@endsection
