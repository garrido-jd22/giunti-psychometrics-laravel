@use('App\Support\Formatter')

{{-- Detalle de producto: breadcrumb, contenido y columna de compra (fija en desktop). --}}
<nav class="breadcrumb u-animate-fade-down" aria-label="Percorso">
    <ol class="breadcrumb__list">
        <li>
            <a href="{{ route('products.index') }}" class="breadcrumb__link">Catalogo</a>
        </li>
        <li class="breadcrumb__item">
            <x-ui.icon name="chevron-right" class="breadcrumb__separator" />
            <a href="{{ $product->type->url() }}" class="breadcrumb__link">{{ $product->type->label() }}</a>
        </li>
        <li class="breadcrumb__item breadcrumb__item--current" aria-current="page">
            <x-ui.icon name="chevron-right" class="breadcrumb__separator" />
            <span class="breadcrumb__current">{{ $product->title }}</span>
        </li>
    </ol>
</nav>

<div class="product-detail">
    <article class="product-detail__main u-animate-fade-up">
        <div class="product-detail__media">
            <img src="{{ $product->image }}" alt="{{ $product->title }}" class="product-detail__image">
        </div>

        <div class="product-detail__badges">
            <span class="product-detail__badge product-detail__badge--type">{{ $product->type->label() }}</span>
            <span class="product-detail__badge product-detail__badge--format">{{ $product->format->label() }}</span>
            <span class="product-detail__badge product-detail__badge--category">{{ $product->category->label() }}</span>
        </div>

        <h1 class="product-detail__title">{{ $product->title }}</h1>

        <p class="product-detail__description">{{ $product->description }}</p>

        <h2 class="product-detail__subtitle">Dettagli</h2>
        <dl class="product-detail__info">
            <x-product.info-row icon="user-round" :label="$product->authorRole">
                <span class="product-detail__author">
                    <span class="avatar avatar--sm">{{ Formatter::initials($product->author) }}</span>
                    {{ $product->author }}
                </span>
            </x-product.info-row>

            @if ($product->meta->isAge())
                <x-product.info-row icon="users" label="Fascia d'età">{{ $product->meta->label }}</x-product.info-row>
            @else
                <x-product.info-row icon="calendar" label="Data di inizio">
                    <time datetime="{{ $product->meta->startDate->toDateString() }}">{{ Formatter::date($product->meta->startDate) }}</time>
                </x-product.info-row>
            @endif

            <x-product.info-row icon="layers" label="Formato">{{ $product->format->label() }}</x-product.info-row>
            <x-product.info-row icon="tag" label="Categoria">{{ $product->category->label() }}</x-product.info-row>
        </dl>
    </article>

    <aside class="product-detail__aside u-animate-fade-up">
        @include('sections.products.purchase-card')
    </aside>
</div>
