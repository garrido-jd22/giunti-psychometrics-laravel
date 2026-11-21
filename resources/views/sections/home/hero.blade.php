<section class="hero">
    <div class="hero__media" aria-hidden="true">
        <video class="hero__video" autoplay loop muted playsinline>
            <source src="{{ asset('videos/gradient.mp4') }}" type="video/mp4">
        </video>
        <div class="hero__overlay"></div>
    </div>

    <div class="hero__content">
        <div class="hero__badge u-animate-fade-down">
            <div class="hero__avatars">
                <span class="hero__avatar hero__avatar--navy">GP</span>
                <span class="hero__avatar hero__avatar--sky">IT</span>
                <span class="hero__avatar hero__avatar--ink">+</span>
            </div>
            <span class="hero__badge-text">10k+ professionisti</span>
        </div>

        <h1 class="hero__title u-animate-fade-down">Inventario di Valutazione Clinica e della Personalità</h1>

        <p class="hero__subtitle u-animate-fade-up">
            Disponibile la nuova edizione del questionario per la valutazione clinica della personalità e della psicopatologia negli adulti.
        </p>

        <div class="hero__cta u-animate-fade-up">
            <a href="{{ route('products.index') }}" class="button button--primary hero__button">Esplora il catalogo</a>
        </div>
    </div>

    <div class="hero__features">
        <ul class="hero__features-list">
            @foreach (config('site.hero_features') as $feature)
                <li class="hero-feature u-animate-fade-up" style="animation-delay: {{ 550 + $loop->index * 90 }}ms">
                    <span class="hero-feature__icon">
                        <x-ui.icon :name="$feature['icon']" :stroke-width="1.8" />
                    </span>
                    <div class="hero-feature__text">
                        <p class="hero-feature__title">{{ $feature['title'] }}</p>
                        <p class="hero-feature__description">{{ $feature['description'] }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</section>
