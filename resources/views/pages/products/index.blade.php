@extends('layouts.page')

@section('title', $type->label().' · Catalogo Prodotti')

@section('page-title', 'Catalogo Prodotti')
@section('page-subtitle', 'Test psicometrici e percorsi di formazione per professionisti.')

@section('page-actions')
    <x-product.tabs :active-type="$type" :counts="$counts" />
@endsection

@section('page-content')
    @include('sections.products.catalog')
@endsection
