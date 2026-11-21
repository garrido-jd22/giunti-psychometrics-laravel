@extends('layouts.page')

@section('title', $product->title)
@section('description', $product->description)

@section('page-content')
    @include('sections.products.detail')
@endsection
