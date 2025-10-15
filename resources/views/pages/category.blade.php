@extends('layouts.app')

@section('title', $page->meta_title ?? $category->name)

@section('content')
    @include('layouts._breadcrumbs', ['items' => $breadcrumbs])

    <section class="category">
        <div class="container">
            <div class="section__container">
                <h1 class="popular__title title">{{ $category->name }}</h1>

                <div class="category__items">
                    @forelse($products as $product)
                        <div class="category__item js-product"
                             data-id="{{ $product->id }}"
                             data-product-name="{{ $product->name }}"
                             data-product-articul="{{ $product->sku ?? '—' }}"
                             data-product-price="{{ $product->price }}"
                             data-product-src="{{ $product->getFirstMediaUrl('cover', 'card') ?: $product->getFirstMediaUrl('cover') ?: asset('img/cat-1.png') }}"
                             data-product-quantity="1"
                             data-montage="0">

                            <div class="category__item-img">
                                {{-- Делаем картинку кликабельной --}}
                                <a href="{{ route('product.show', $product->slug) }}" class="category__item-img-link">
                                    <img src="{{ $product->getFirstMediaUrl('cover', 'card') ?: $product->getFirstMediaUrl('cover') ?: asset('img/cat-1.png') }}"
                                         alt="{{ $product->name }}">
                                </a>

                                {{-- Кнопка "в корзину" остаётся как есть --}}
                                <a href="javascript:;" class="category__item-buy js-buy">
                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.2812 23.625C10.2812 23.9279 10.1914 24.2239 10.0232 24.4757C9.85493 24.7275 9.61578 24.9238 9.33598 25.0397C9.05618 25.1556 8.7483 25.1859 8.45127 25.1268C8.15423 25.0677 7.88139 24.9219 7.66724 24.7078C7.45309 24.4936 7.30726 24.2208 7.24817 23.9237C7.18909 23.6267 7.21941 23.3188 7.33531 23.039C7.45121 22.7592 7.64747 22.5201 7.89928 22.3518C8.1511 22.1836 8.44715 22.0938 8.75 22.0938C9.15611 22.0938 9.54559 22.2551 9.83276 22.5422C10.1199 22.8294 10.2812 23.2189 10.2812 23.625ZM20.125 22.0938C19.8221 22.0938 19.5261 22.1836 19.2743 22.3518C19.0225 22.5201 18.8262 22.7592 18.7103 23.039C18.5944 23.3188 18.5641 23.6267 18.6232 23.9237C18.6823 24.2208 18.8281 24.4936 19.0422 24.7078C19.2564 24.9219 19.5292 25.0677 19.8263 25.1268C20.1233 25.1859 20.4312 25.1556 20.711 25.0397C20.9908 24.9238 21.2299 24.7275 21.3982 24.4757C21.5664 24.2239 21.6562 23.9279 21.6562 23.625C21.6562 23.2189 21.4949 22.8294 21.2078 22.5422C20.9206 22.2551 20.5311 22.0938 20.125 22.0938ZM24.8828 8.05L21.9953 18.1562C21.8491 18.6589 21.5444 19.1009 21.1265 19.4163C20.7087 19.7316 20.2001 19.9035 19.6766 19.9062H9.19844C8.67494 19.9035 8.16635 19.7316 7.74849 19.4163C7.33063 19.1009 7.02589 18.6589 6.87969 18.1562L3.99219 8.07188V8.03906L2.92031 4.32031C2.91015 4.27307 2.88384 4.23085 2.84592 4.2009C2.80799 4.17096 2.76081 4.15517 2.7125 4.15625H0.875C0.700952 4.15625 0.534032 4.08711 0.410961 3.96404C0.28789 3.84097 0.21875 3.67405 0.21875 3.5C0.21875 3.32595 0.28789 3.15903 0.410961 3.03596C0.534032 2.91289 0.700952 2.84375 0.875 2.84375H2.7125C3.04618 2.84493 3.37048 2.95431 3.63672 3.15547C3.90296 3.35662 4.09677 3.63871 4.18906 3.95938L5.11875 7.21875H24.2484C24.3503 7.21875 24.4508 7.24247 24.5419 7.28803C24.633 7.33359 24.7123 7.39975 24.7734 7.48125C24.834 7.56137 24.8753 7.6543 24.8943 7.75291C24.9132 7.85151 24.9093 7.95315 24.8828 8.05Z" fill="white"/>
                                    </svg>
                                </a>
                            </div>

                            <div class="category__item-block">
                                <div class="category__item-price">
                                    {{ number_format($product->price, 0, ',', ' ') }} ₸
                                </div>

                                {{-- Заголовок уже ведёт на товар --}}
                                <a href="{{ route('product.show', $product->slug) }}" class="category__item-link">
                                    {{ $product->name }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <p>Товары скоро появятся.</p>
                    @endforelse
                </div>

                {{-- Пагинация --}}
                @if($products->hasPages())
                    <ul class="pagination">
                        {{-- Предыдущая --}}
                        @if($products->onFirstPage())
                            <li class="arrow disabled">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M17.6191 19L12.2885 12.7809C11.9033 12.3316 11.9033 11.6684 12.2885 11.2191L17.6191 5"
                                              stroke="#FF6600" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </span>
                            </li>
                        @else
                            <li class="arrow">
                                <a href="{{ $products->previousPageUrl() }}">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M17.6191 19L12.2885 12.7809C11.9033 12.3316 11.9033 11.6684 12.2885 11.2191L17.6191 5"
                                              stroke="#FF6600" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </a>
                            </li>
                        @endif

                        {{-- Номера страниц --}}
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            <li class="{{ $page == $products->currentPage() ? 'active' : '' }}">
                                <a href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        {{-- Следующая --}}
                        @if ($products->hasMorePages())
                            <li class="arrow">
                                <a href="{{ $products->nextPageUrl() }}">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M6.38086 19L11.7115 12.7809C12.0967 12.3316 12.0967 11.6684 11.7115 11.2191L6.38086 5"
                                              stroke="#FF6600" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </a>
                            </li>
                        @else
                            <li class="arrow disabled">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M6.38086 19L11.7115 12.7809C12.0967 12.3316 12.0967 11.6684 11.7115 11.2191L6.38086 5"
                                              stroke="#FF6600" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </span>
                            </li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>
    </section>
@endsection
