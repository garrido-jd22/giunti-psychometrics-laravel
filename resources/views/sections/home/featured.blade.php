<section class="featured">
    <div class="container">
        <div class="featured__header u-animate-fade-up">
            <div>
                <span class="featured__eyebrow">Catalogo &amp; Formazione</span>
                <h2 class="featured__title">Strumenti psicometrici e corsi in evidenza</h2>
            </div>
            <a href="{{ route('products.index') }}" class="featured__link">
                Esplora tutto il catalogo
                <x-ui.icon name="arrow-right" />
            </a>
        </div>

        {{-- Carga simulada: skeleton y después las cards (modules/loadable.js) --}}
        <div class="loadable" data-loadable>
            <div class="loadable__skeleton">
                <x-product.grid :products="$featuredProducts" loading :skeleton-count="$featuredProducts->count()" />
            </div>
            <div class="loadable__content">
                <x-product.grid :products="$featuredProducts" />
            </div>
        </div>
    </div>
</section>
