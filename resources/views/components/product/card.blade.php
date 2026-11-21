@use('App\Support\Formatter')

@props(['product'])

<article {{ $attributes->merge(['class' => 'product-card']) }}>
    <div>
        <div class="product-card__media">
            <img src="{{ $product->image }}" alt="{{ $product->title }}" loading="lazy" class="product-card__image">

            <span class="product-card__format">{{ $product->format->label() }}</span>

            <button type="button" class="product-card__bookmark" aria-label="Salva nei preferiti">
                <x-ui.icon name="bookmark" :stroke-width="1.9" />
            </button>

            <div class="product-card__price">
                @if ($product->originalPrice !== null)
                    <span class="product-card__price-original">{{ Formatter::euro($product->originalPrice) }}</span>
                @endif
                <span class="product-card__price-current">{{ Formatter::euro($product->price) }}</span>
            </div>
        </div>

        <div class="product-card__body">
            <h3 class="product-card__title">
                {{-- ::after cubre toda la tarjeta y la hace clicable --}}
                <a href="{{ $product->url() }}" class="product-card__link">{{ $product->title }}</a>
            </h3>

            <p class="product-card__description">{{ $product->description }}</p>

            <div class="product-card__meta">
                <span class="product-card__chip">
                    @if ($product->meta->isAge())
                        <x-ui.icon name="users" class="product-card__chip-icon" />
                        {{ $product->meta->label }}
                    @else
                        <x-ui.icon name="calendar" class="product-card__chip-icon" />
                        <time datetime="{{ $product->meta->startDate->toDateString() }}">
                            Inizio: {{ Formatter::date($product->meta->startDate) }}
                        </time>
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="product-card__footer">
        <div class="product-card__author">
            <span class="avatar">{{ Formatter::initials($product->author) }}</span>
            <div class="product-card__author-text">
                <p class="product-card__author-name">{{ $product->author }}</p>
                <p class="product-card__author-role">{{ $product->authorRole }}</p>
            </div>
        </div>

        <div class="product-card__action">
            @if ($product->addsToCart())
                <x-cart.add-button :product="$product" />
            @else
                <a href="{{ $product->url() }}" class="button button--primary button--pill">
                    Scopri di più
                    <x-ui.icon name="arrow-right" class="button__icon--sm" />
                </a>
            @endif
        </div>
    </div>
</article>
