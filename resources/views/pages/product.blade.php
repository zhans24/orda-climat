@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name)

@section('content')
    @include('layouts._breadcrumbs', ['items' => $breadcrumbs])

    <section class="product">
        <div class="container">
            <div class="section__container">
                <div class="product__block">
                    {{-- ЛЕВАЯ КОЛОНКА: ГАЛЕРЕЯ (классы не трогаем) --}}
                    <div class="product__left">
                        @php
                            $coverUrl   = $product->getFirstMediaUrl('cover', 'xl') ?: $product->getFirstMediaUrl('cover') ?: asset('img/product-1.jpg');
                            $thumbCover = $product->getFirstMediaUrl('cover', 'card') ?: $product->getFirstMediaUrl('cover') ?: asset('img/product-1.jpg');
                            $gallery    = $product->getMedia('gallery');
                        @endphp

                        <div class="product__image swiper swiper-fade swiper-horizontal swiper-pointer-events swiper-watch-progress swiper-backface-hidden">
                            <div class="swiper-wrapper" aria-live="polite">
                                <div class="product__image-slide swiper-slide swiper-slide-visible swiper-slide-active"
                                     data-fancybox="product" data-src="{{ $coverUrl }}">
                                    <img src="{{ $thumbCover }}" alt="{{ $product->name }}">
                                </div>
                                @foreach($gallery as $media)
                                    @php
                                        $full = $media->getUrl('xl') ?: $media->getUrl();
                                        $img  = $media->getUrl('card') ?: $media->getUrl();
                                    @endphp
                                    <div class="product__image-slide swiper-slide"
                                         data-fancybox="product" data-src="{{ $full }}">
                                        <img src="{{ $img }}" alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                        </div>

                        <div class="product__thumbs swiper swiper-horizontal swiper-pointer-events swiper-backface-hidden swiper-thumbs">
                            <div class="swiper-wrapper" aria-live="polite">
                                <div class="product__thumbs-slide swiper-slide swiper-slide-visible swiper-slide-active swiper-slide-thumb-active">
                                    <img src="{{ $thumbCover }}" alt="{{ $product->name }}">
                                </div>
                                @foreach($gallery as $media)
                                    <div class="product__thumbs-slide swiper-slide swiper-slide-visible">
                                        <img src="{{ $media->getUrl('card') ?: $media->getUrl() }}" alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                        </div>
                    </div>

                    {{-- ПРАВАЯ КОЛОНКА: ИНФО/ЦЕНА/КУПИТЬ (классы не меняем) --}}
                    <div class="product__right js-product"
                         data-id="{{ $product->id }}"
                         data-product-name="{{ $product->name }}"
                         data-product-articul="{{ $product->sku ?? '—' }}"
                         data-product-price="{{ number_format($product->price, 2, '.', '') }}"
                         data-product-src="{{ $thumbCover }}"
                         data-product-quantity="1"
                         data-montage="0">

                        <h1 class="product__title title">{{ $product->name }}</h1>

                        <div class="product__art">
                            <span>{{ $product->sku ? 'Артикул: '.$product->sku : 'Артикул' }}</span>
                            <div class="product__tag">{{ $product->is_available ? 'В наличии' : 'Нет в наличии' }}</div>
                        </div>

                        <div class="product__price">{{ number_format($product->price, 0, ',', ' ') }} T</div>

                        <div class="product__elems">
                            <div class="product__counter">
                                <button class="product__btn js-product-decrease decrease">
                                    <svg width="9" height="2" viewBox="0 0 9 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 2V0H9V2H0Z" fill="#FF6600"></path>
                                    </svg>
                                </button>
                                <span>1</span>
                                <button class="product__btn js-product-increase increase">
                                    <svg width="11" height="10" viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.19938 9.88004V6.08004H0.359375V3.84004H4.19938V0.0400391H6.47938V3.84004H10.3194V6.08004H6.47938V9.88004H4.19938Z" fill="#FF6600"></path>
                                    </svg>
                                </button>
                            </div>

                            <a href="javascript:;" class="product__buy js-buy">
                                <span>
                                  В корзину
                                  <svg width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.5781 30.5312C14.5781 30.9098 14.4659 31.2799 14.2555 31.5946C14.0452 31.9094 13.7463 32.1547 13.3965 32.2996C13.0468 32.4445 12.6619 32.4824 12.2906 32.4085C11.9194 32.3347 11.5783 32.1524 11.3106 31.8847C11.0429 31.617 10.8606 31.276 10.7868 30.9047C10.7129 30.5334 10.7508 30.1485 10.8957 29.7988C11.0406 29.449 11.2859 29.1501 11.6007 28.9398C11.9154 28.7294 12.2855 28.6172 12.6641 28.6172C13.1717 28.6172 13.6586 28.8188 14.0175 29.1778C14.3765 29.5368 14.5781 30.0236 14.5781 30.5312ZM26.8828 28.6172C26.5042 28.6172 26.1342 28.7294 25.8194 28.9398C25.5047 29.1501 25.2593 29.449 25.1145 29.7988C24.9696 30.1485 24.9317 30.5334 25.0055 30.9047C25.0794 31.276 25.2617 31.617 25.5294 31.8847C25.7971 32.1524 26.1381 32.3347 26.5094 32.4085C26.8807 32.4824 27.2655 32.4445 27.6153 32.2996C27.965 32.1547 28.264 31.9094 28.4743 31.5946C28.6846 31.2799 28.7969 30.9098 28.7969 30.5312C28.7969 30.0236 28.5952 29.5368 28.2363 29.1778C27.8773 28.8188 27.3905 28.6172 26.8828 28.6172ZM32.8301 11.0625L29.2207 23.6953C29.038 24.3237 28.657 24.8761 28.1347 25.2703C27.6124 25.6645 26.9766 25.8794 26.3223 25.8828H13.2246C12.5702 25.8794 11.9345 25.6645 11.4122 25.2703C10.8898 24.8761 10.5089 24.3237 10.3262 23.6953L6.7168 11.0898V11.0488L5.37695 6.40039C5.36425 6.34134 5.33137 6.28856 5.28396 6.25113C5.23655 6.2137 5.17758 6.19396 5.11719 6.19531H2.82031C2.60275 6.19531 2.3941 6.10889 2.24026 5.95505C2.08643 5.80121 2 5.59256 2 5.375C2 5.15744 2.08643 4.94879 2.24026 4.79495C2.3941 4.64111 2.60275 4.55469 2.82031 4.55469H5.11719C5.53429 4.55616 5.93967 4.69289 6.27246 4.94433C6.60526 5.19578 6.84752 5.54838 6.96289 5.94922L8.125 10.0234H32.0371C32.1645 10.0234 32.2901 10.0531 32.404 10.11C32.5179 10.167 32.6169 10.2497 32.6934 10.3516C32.769 10.4517 32.8207 10.5679 32.8444 10.6911C32.8681 10.8144 32.8632 10.9414 32.8301 11.0625Z" fill="white"/>
                                  </svg>
                                </span>
                                <span>В корзине</span>
                            </a>

                            <a href="javascript:;" class="product__click js-modal-req">заказать в 1 клик</a>
                        </div>

                        {{-- Доставка / Оплата из страницы `products` (с дефолтами, если пусто) --}}
                        @php
                            $deliveryTitleRaw = data_get($productsPage, 'content.product_delivery_title');
                            $deliveryTitle    = trim((string) $deliveryTitleRaw) !== '' ? $deliveryTitleRaw : 'Доставка';
                            $deliveryHtml     = data_get($productsPage, 'content.product_delivery_html');

                            $paymentTitleRaw  = data_get($productsPage, 'content.product_payment_title');
                            $paymentTitle     = trim((string) $paymentTitleRaw) !== '' ? $paymentTitleRaw : 'Оплата';
                            $paymentHtml      = data_get($productsPage, 'content.product_payment_html');
                        @endphp

                        <div class="product__info">
                            <div class="product__info-title">{{ $deliveryTitle }}</div>
                            @if(filled($deliveryHtml))
                                {!! $deliveryHtml !!}
                            @endif
                        </div>

                        <div class="product__info">
                            <div class="product__info-title">{{ $paymentTitle }}</div>
                            @if(filled($paymentHtml))
                                {!! $paymentHtml !!}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ОПИСАНИЕ / ХАРАКТЕРИСТИКИ (классы исходные) --}}
                <div class="product__desc">
                    <div class="product__info-header">
                        <a href="javascript:;" class="product__info-link active" data-id="1">Описание</a>
                        <a href="javascript:;" class="product__info-link" data-id="2">Характеристики</a>
                    </div>

                    <div class="product__content">
                        <div class="product__content-block active" data-id="1">
                            @if(!empty($product->description))
                                {!! $product->description !!}
                            @else
                                <p>Описание появится позже.</p>
                            @endif
                        </div>

                        @php
                            $specs = $product->specifications ?? [];
                            $main  = collect($specs['Основные'] ?? []);
                            $extra = collect($specs['Дополнительные'] ?? []);
                        @endphp

                        <div class="product__content-block" data-id="2">
                            <div class="product__wrapper">
                                <div class="product__col">
                                    <div class="product__col-title">Основные</div>
                                    <ul>
                                        @forelse($main as $row)
                                            <li><span>{{ $row['k'] ?? '' }}</span><span>{{ $row['v'] ?? '' }}</span></li>
                                        @empty
                                            <li><span>—</span><span>—</span></li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="product__col">
                                    <div class="product__col-title">Дополнительные функции и характеристики</div>
                                    <ul>
                                        @forelse($extra as $row)
                                            <li><span>{{ $row['k'] ?? '' }}</span><span>{{ $row['v'] ?? '' }}</span></li>
                                        @empty
                                            <li><span>—</span><span>—</span></li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Похожие товары (кликабельно по картинке, названию и цене) --}}
                @if($related->isNotEmpty())
                    <section class="recomend">
                        <div class="container">
                            <div class="recomend__container">
                                <div class="recomend__header">
                                    <h2 class="recomend__title title">Похожие товары</h2>
                                    <div class="recomend__arrows">
                                        <a href="javascript:;" class="recomend__arrow prev">
                                            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="25" cy="25" r="25" transform="matrix(-1 0 0 1 50 0)" fill="#F2F4FF"></circle>
                                                <path d="M30.6191 32L25.2885 25.7809C24.9033 25.3316 24.9033 24.6684 25.2885 24.2191L30.6191 18" stroke="#2B308B" stroke-width="1.5" stroke-linecap="round"></path>
                                                <path d="M24.6191 32L19.2885 25.7809C18.9033 25.3316 18.9033 24.6684 19.2885 24.2191L24.6191 18" stroke="#2B308B" stroke-width="1.5" stroke-linecap="round"></path>
                                            </svg>
                                        </a>
                                        <a href="javascript:;" class="recomend__arrow next">
                                            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="25" cy="25" r="25" fill="#F2F4FF"></circle>
                                                <path d="M19.3809 32L24.7115 25.7809C25.0967 25.3316 25.0967 24.6684 24.7115 24.2191L19.3809 18" stroke="#2B308B" stroke-width="1.5" stroke-linecap="round"></path>
                                                <path d="M25.3809 32L30.7115 25.7809C31.0967 25.3316 31.0967 24.6684 30.7115 24.2191L25.3809 18" stroke="#2B308B" stroke-width="1.5" stroke-linecap="round"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                <div class="recomend__slider swiper">
                                    <div class="swiper-wrapper">
                                        @foreach($related as $p)
                                            @php
                                                $pImg = $p->getFirstMediaUrl('cover','card') ?: $p->getFirstMediaUrl('cover') ?: asset('img/cat-1.png');
                                                $pUrl = route('product.show', $p->slug);
                                            @endphp
                                            <div class="category__item swiper-slide js-product"
                                                 data-id="{{ $p->id }}"
                                                 data-product-name="{{ $p->name }}"
                                                 data-product-articul="{{ $p->sku ?? '—' }}"
                                                 data-product-price="{{ number_format($p->price, 2, '.', '') }}"
                                                 data-product-src="{{ $pImg }}"
                                                 data-product-quantity="1"
                                                 data-montage="0">

                                                <div class="category__item-img">
                                                    <a href="{{ $pUrl }}" class="category__item-img-link">
                                                        <img src="{{ $pImg }}" alt="{{ $p->name }}">
                                                    </a>
                                                    <a href="javascript:;" class="category__item-buy js-buy">
                                                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M10.2812 23.625C10.2812 23.9279 10.1914 24.2239 10.0232 24.4757C9.85493 24.7275 9.61578 24.9238 9.33598 25.0397C9.05618 25.1556 8.7483 25.1859 8.45127 25.1268C8.15423 25.0677 7.88139 24.9219 7.66724 24.7078C7.45309 24.4936 7.30726 24.2208 7.24817 23.9237C7.18909 23.6267 7.21941 23.3188 7.33531 23.039C7.45121 22.7592 7.64747 22.5201 7.89928 22.3518C8.1511 22.1836 8.44715 22.0938 8.75 22.0938C9.15611 22.0938 9.54559 22.2551 9.83276 22.5422C10.1199 22.8294 10.2812 23.2189 10.2812 23.625ZM20.125 22.0938C19.8221 22.0938 19.5261 22.1836 19.2743 22.3518C19.0225 22.5201 18.8262 22.7592 18.7103 23.039C18.5944 23.3188 18.5641 23.6267 18.6232 23.9237C18.6823 24.2208 18.8281 24.4936 19.0422 24.7078C19.2564 24.9219 19.5292 25.0677 19.8263 25.1268C20.1233 25.1859 20.4312 25.1556 20.711 25.0397C20.9908 24.9238 21.2299 24.7275 21.3982 24.4757C21.5664 24.2239 21.6562 23.9279 21.6562 23.625C21.6562 23.2189 21.4949 22.8294 21.2078 22.5422C20.9206 22.2551 20.5311 22.0938 20.125 22.0938ZM24.8828 8.05L21.9953 18.1562C21.8491 18.6589 21.5444 19.1009 21.1265 19.4163C20.7087 19.7316 20.2001 19.9035 19.6766 19.9062H9.19844C8.67494 19.9035 8.16635 19.7316 7.74849 19.4163C7.33063 19.1009 7.02589 18.6589 6.87969 18.1562L3.99219 8.07188V8.03906L2.92031 4.32031C2.91015 4.27307 2.88384 4.23085 2.84592 4.2009C2.80799 4.17096 2.76081 4.15517 2.7125 4.15625H0.875C0.700952 4.15625 0.534032 4.08711 0.410961 3.96404C0.28789 3.84097 0.21875 3.67405 0.21875 3.5C0.21875 3.32595 0.28789 3.15903 0.410961 3.03596C0.534032 2.91289 0.700952 2.84375 0.875 2.84375H2.7125C3.04618 2.84493 3.37048 2.95431 3.63672 3.15547C3.90296 3.35662 4.09677 3.63871 4.18906 3.95938L5.11875 7.21875H24.2484C24.3503 7.21875 24.4508 7.24247 24.5419 7.28803C24.633 7.33359 24.7123 7.39975 24.7734 7.48125C24.834 7.56137 24.8753 7.6543 24.8943 7.75291C24.9132 7.85151 24.9093 7.95315 24.8828 8.05Z" fill="white"/>
                                                        </svg>
                                                    </a>
                                                </div>

                                                <div class="category__item-block">
                                                    <a href="{{ $pUrl }}" class="category__item-price">{{ number_format($p->price, 0, ',', ' ') }} T</a>
                                                    <a href="{{ $pUrl }}" class="category__item-link">{{ $p->name }}</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                @endif

            </div>
        </div>
    </section>
@endsection
