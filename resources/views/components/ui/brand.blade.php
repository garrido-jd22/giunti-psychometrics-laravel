{{-- Logo de la marca (header y footer). --}}
<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'brand']) }}>
    <span class="brand__logo">
        <x-ui.icon name="galaxy" class="brand__logo-icon" :stroke-width="1.8" />
    </span>
    <span class="brand__name">
        @foreach (config('site.brand') as $line)
            <span class="brand__line">{{ $line }}</span>
        @endforeach
    </span>
</a>
