<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @hasSection('description')
            <meta name="description" content="@yield('description')">
        @endif

        <title>@yield('title', config('site.name'))</title>

        {{-- Aplica el tema guardado (o el del sistema) antes del primer pintado para evitar el parpadeo. --}}
        <script>
            (function () {
                var root = document.documentElement;
                {{-- Con JS activo los bloques .loadable arrancan mostrando su skeleton (sin parpadeo). --}}
                root.classList.add('js');
                try {
                    var theme = localStorage.getItem('theme');
                    if (theme !== 'light' && theme !== 'dark') {
                        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    }
                    root.classList.toggle('dark', theme === 'dark');
                    root.style.colorScheme = theme;
                } catch (error) {}
            })();
        </script>

        @vite(['resources/stylus/app.styl', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="body @yield('body-class')" data-search-timing='@json(config('products.search'))'>
        @hasSection('bare')
            @yield('content')
        @else
            <x-layout.header />

            <main class="main">
                @yield('content')
            </main>

            <x-layout.footer />

            <x-cart.drawer />
        @endif

        <x-ui.toaster />

        @stack('scripts')
    </body>
</html>
