{{-- Secciones: page-title, page-subtitle, page-actions, page-content. Sin page-title no hay cabecera. --}}
@extends('layouts.app')

@section('content')
    <section class="page">
        <div class="container">
            @hasSection('page-title')
                <div class="page__header">
                    <div class="page__heading u-animate-fade-down">
                        <h1 class="page__title">@yield('page-title')</h1>
                        <p class="page__subtitle">@yield('page-subtitle')</p>
                    </div>

                    @hasSection('page-actions')
                        <div class="page__actions u-animate-fade-up">
                            @yield('page-actions')
                        </div>
                    @endif
                </div>
            @endif

            @yield('page-content')
        </div>
    </section>
@endsection
