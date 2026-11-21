@use('App\Support\Link')

@php
    $footer = config('site.footer');
    $contactUrl = route('home').'#'.config('contact.section_id');
@endphp

<footer class="footer">
    <div class="footer__inner u-animate-fade-in">
        <div class="footer__grid">
            <div class="footer__brand">
                <x-ui.brand />
                <p class="footer__tagline">{{ config('site.tagline') }}</p>
            </div>

            @foreach ($footer['columns'] as $column)
                <div class="footer__column footer__column--{{ $loop->iteration }}">
                    <h3 class="footer__title">{{ $column['title'] }}</h3>
                    <ul class="footer__links">
                        @foreach ($column['links'] as $link)
                            <li>
                                <a href="{{ Link::url($link) }}" class="footer__link">{{ $link['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="footer__aside">
                <div class="footer__social">
                    @foreach ($footer['social'] as $network => $label)
                        <a href="#" class="footer__social-link" aria-label="{{ $label }}">
                            @include('icons.social.'.$network)
                        </a>
                    @endforeach
                </div>

                <a href="{{ $contactUrl }}" class="footer__contact">Contattaci</a>
            </div>
        </div>

        <hr class="footer__divider">

        <div class="footer__bottom">
            <div class="footer__legal">
                <p>{{ $footer['legal']['copyright'] }}</p>
                <p>
                    {{ $footer['legal']['dpo'] }}
                    <a href="mailto:{{ $footer['legal']['dpo_email'] }}" class="footer__mail">{{ $footer['legal']['dpo_email'] }}</a>
                </p>
            </div>

            <div class="footer__policies">
                <a href="#" class="footer__policy">Informativa Privacy</a>
                <a href="#" class="footer__policy">Informativa Cookie</a>
            </div>
        </div>
    </div>
</footer>
