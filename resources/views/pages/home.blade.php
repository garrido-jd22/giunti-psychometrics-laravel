@extends('layouts.app')

@section('body-class', 'body--home')

@section('content')
    @include('sections.home.hero')
    @include('sections.home.featured')
    @include('sections.home.contact')
@endsection
