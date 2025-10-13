@php use App\Models\Slider; @endphp
@extends('layouts.app')

@section('title', $page->meta_title ?? 'Главная')

@section('content')
    {{-- HERO --}}
    @php
        $slider = Slider::with(['slides' => fn($q) => $q->where('is_active', true)->orderBy('position')])
          ->where('key', 'home_hero')
          ->where('is_active', true)
          ->first();
        $slides = $slider?->slides ?? collect();

        $c = (array) ($page->content ?? []);

        // INSTA
        $instaTitle    = data_get($c, 'insta_title', 'Подпишись на нас в Instagram');
        $instaSubtitle = data_get($c, 'insta_subtitle', 'Чтобы быть первыми в курсе новинок');
        $instaLink     = data_get($c, 'insta_link', '#');

        $instaMedia = $page->getMedia('insta_images'); // до 4 файлов
        $instaCols = [
            $instaMedia->get(0),
            $instaMedia->get(1),
            $instaMedia->get(2),
            $instaMedia->get(3),
        ];

        // CUSTOM / УСЛУГИ
        $textSlides   = collect(data_get($c, 'services_slides', []));
        $imageSlides  = $page->getMedia('services_images');
    @endphp

    @if($slides->count())
        <section class="hero">
            <div class="hero__images swiper">
                <div class="swiper-wrapper">
                    @foreach($slides as $slide)
                        <div class="swiper-slide">
                            <img
                                src="{{ $slide->getFirstMediaUrl('slide_image', 'webp') ?: asset('img/placeholder.jpg') }}"
                                alt="{{ $slide->title ?? 'Слайд' }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="container">
                <div class="hero__container">
                    <a href="javascript:" class="hero__arrow prev">
                        {{-- SVG как в верстке --}}
                        <svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="27.5" cy="27.5" r="27.5" transform="matrix(-1 0 0 1 55 0)" fill="#FFEEE2"/>
                            <path d="M30 34L24 28L30 22" stroke="#FF6600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <div class="hero__text swiper">
                        <div class="swiper-wrapper">
                            @foreach($slides as $slide)
                                <div class="hero__text-slide swiper-slide">
                                    @if($slide->title)
                                        <div class="hero__text-title">{{ $slide->title }}</div>
                                    @endif
                                    @if($slide->subtitle)
                                        <div class="hero__text-desc">{{ $slide->subtitle }}</div>
                                    @endif

                                    @php $btnText = $slide->button_text ?: 'Оставить заявку'; @endphp
                                    @if(!empty($slide->button_link))
                                        <a href="{{ $slide->button_link }}" class="hero__text-link"
                                           @if(Str::startsWith($slide->button_link, ['http://','https://'])) target="_blank" @endif>
                                            {{ $btnText }}
                                        </a>
                                    @else
                                        <a href="javascript:" class="hero__text-link js-modal-req">{{ $btnText }}</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <a href="javascript:" class="hero__arrow next">
                        <svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="27.5" cy="27.5" r="27.5" fill="#FFEEE2"/>
                            <path d="M25 34L31 28L25 22" stroke="#FF6600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <div class="hero__pagination"></div>
                </div>
            </div>
        </section>
    @endif

    {{-- POPULAR (важно, чтобы классы внутри совпадали с версткой) --}}
    <section class="popular">
        <div class="container">
            <div class="popular__container">
                <h2 class="popular__title title">Популярные категории</h2>

                <div class="popular__items">
                    @forelse($popularCategories as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="popular__item">
                            <div class="popular__item-img">
                                @php
                                    $img = $cat->getFirstMediaUrl('cover', 'card')
                                        ?: $cat->getFirstMediaUrl('cover')
                                        ?: asset('img/popular.jpg');
                                @endphp
                                <img src="{{ $img }}" alt="{{ $cat->name }}">
                            </div>

                            <div class="popular__item-bot">
                                <div class="popular__item-name">{{ $cat->name }}</div>
                                <div class="popular__item-btn">Подробнее</div>
                            </div>
                        </a>
                    @empty
                        <p>Категории скоро появятся.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- INSTA --}}
    {{-- INSTA (из админки) --}}
    <section class="insta">
        <div class="container">
            <div class="insta__container">
                <div class="insta__images">
                    <div class="insta__col">
                        @if($instaCols[0]) <img src="{{ $instaCols[0]->getUrl('webp') ?: $instaCols[0]->getUrl() }}" alt=""> @endif
                        @if($instaCols[1]) <img src="{{ $instaCols[1]->getUrl('webp') ?: $instaCols[1]->getUrl() }}" alt=""> @endif
                    </div>
                    <div class="insta__col">
                        @if($instaCols[2]) <img src="{{ $instaCols[2]->getUrl('webp') ?: $instaCols[2]->getUrl() }}" alt=""> @endif
                        @if($instaCols[3]) <img src="{{ $instaCols[3]->getUrl('webp') ?: $instaCols[3]->getUrl() }}" alt=""> @endif
                    </div>
                </div>

                <div class="insta__right">
                    <h2 class="insta__title">{{ $instaTitle }}</h2>
                    <div class="insta__subtitle">{{ $instaSubtitle }}</div>

                    {{-- декоративная линия (проще, но визуально ок) --}}
                    <svg width="276" height="7" viewBox="0 0 276 7" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect width="276" height="6.0262" fill="url(#pattern0_5_2741)"></rect>
                        <mask id="mask0_5_2741" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="276" height="7">
                            <rect width="276" height="6.0262" fill="url(#pattern1_5_2741)"></rect>
                        </mask>
                        <g mask="url(#mask0_5_2741)">
                            <rect width="276" height="6.0262" fill="#FF6600"></rect>
                        </g>
                        <defs>
                            <pattern id="pattern0_5_2741" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_5_2741" transform="scale(0.0010917 0.05)"></use>
                            </pattern>
                            <pattern id="pattern1_5_2741" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_5_2741" transform="scale(0.0010917 0.05)"></use>
                            </pattern>
                            <image id="image0_5_2741" width="916" height="20" preserveAspectRatio="none" xlink:href="data:image/png;base64,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"></image>
                        </defs>
                    </svg>
                    @if($instaLink)
                        <a href="{{ $instaLink }}" target="_blank" class="insta__link">Подписаться</a>
                    @endif
                </div>
            </div>
        </div>
    </section>


    <section class="custom">
        <div class="custom__container">

            {{-- ТЕКСТ --}}
            <div class="custom__text swiper">
                <div class="swiper-wrapper">
                    @foreach($textSlides as $i => $slide)
                        @php
                            $title   = data_get($slide, 'title', 'Услуги');
                            $links   = collect(data_get($slide, 'links', []));   // [{label,url}, ...]
                            $bullets = collect(data_get($slide, 'bullets', [])); // ['Качество', ...] или [{text: '...'}]
                        @endphp
                        <div class="swiper-slide">
                            <div class="custom__text-slide">
                                <h3>{{ $title }}</h3>

                                {{-- две строки ссылок как в макете --}}
                                @foreach($links as $k => $lnk)
                                    <a href="{{ data_get($lnk,'url','#') }}">
                                        {{ data_get($lnk,'label','Ссылка') }}@if(!$loop->last),@endif
                                    </a>
                                    @if($k==0)<br>@endif
                                @endforeach

                                {{-- оранжевая полоса --}}
                                <svg width="276" height="7" viewBox="0 0 276 7" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <rect width="276" height="6.0262" fill="url(#pattern0_5_2741)"></rect>
                                    <mask id="mask0_5_2741" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="276" height="7">
                                        <rect width="276" height="6.0262" fill="url(#pattern1_5_2741)"></rect>
                                    </mask>
                                    <g mask="url(#mask0_5_2741)">
                                        <rect width="276" height="6.0262" fill="#FF6600"></rect>
                                    </g>
                                    <defs>
                                        <pattern id="pattern0_5_2741" patternContentUnits="objectBoundingBox" width="1" height="1">
                                            <use xlink:href="#image0_5_2741" transform="scale(0.0010917 0.05)"></use>
                                        </pattern>
                                        <pattern id="pattern1_5_2741" patternContentUnits="objectBoundingBox" width="1" height="1">
                                            <use xlink:href="#image0_5_2741" transform="scale(0.0010917 0.05)"></use>
                                        </pattern>
                                        <image id="image0_5_2741" width="916" height="20" preserveAspectRatio="none" xlink:href="data:image/png;base64,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"></image>
                                    </defs>
                                </svg>

                                {{-- буллеты с чек-иконкой --}}
                                @if($bullets->isNotEmpty())
                                    <ul>
                                        @foreach($bullets as $b)
                                            @php $txt = is_array($b) ? ($b['text'] ?? '') : $b; @endphp
                                            <li>
                                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path d="M21.0001 10.0862V11.0062C20.9975 15.4349 18.0824 19.3344 13.8354 20.5901C9.58847 21.8458 5.02145 20.1585 2.61101 16.4433C0.200573 12.7281 0.52092 7.86987 3.39833 4.50331C6.27574 1.13674 11.0248 0.0637601 15.0701 1.86623" stroke="#2B308B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M21 3.00635L11 13.0163L8 10.0163" stroke="#2B308B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                {{ $txt }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ИЗОБРАЖЕНИЯ --}}
            <div class="custom__images swiper">
                <div class="swiper-wrapper">
                    @foreach($imageSlides as $img)
                        <div class="swiper-slide custom__images-slide">
                            <img src="{{ $img->getUrl('webp') ?: $img->getUrl() }}" alt="">
                        </div>
                    @endforeach
                </div>

                {{-- точки пагинации (как у тебя) --}}
                <div class="custom__pagination"></div>

                {{-- стрелки для ручного переключения --}}
                <button class="custom__arrow prev" type="button" aria-label="Предыдущий">
                    <svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="27.5" cy="27.5" r="27.5" transform="matrix(-1 0 0 1 55 0)" fill="#FFEEE2"/>
                        <path d="M30 34L24 28L30 22" stroke="#FF6600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button class="custom__arrow next" type="button" aria-label="Следующий">
                    <svg width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="27.5" cy="27.5" r="27.5" fill="#FFEEE2"/>
                        <path d="M25 34L31 28L25 22" stroke="#FF6600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>


    {{-- МОДАЛКА --}}
    <div class="modal js-modal">
        <form action="{{ route('lead.store') }}" method="post">
            @csrf
            <div class="modal__block">
                <div class="modal__block-title title">{{ $page?->sections['modal_title'] ?? 'Мы свяжемся с вами' }}</div>
                <div class="modal__block-desc">{{ $page?->sections['modal_subtitle'] ?? 'Мы ответим на все вопросы, уточним детали и предоставим необходимую информацию.' }}</div>
                <input type="text" name="name" placeholder="Имя">
                <input type="tel" name="phone" placeholder="Телефон">
                <textarea name="message" placeholder="Сообщение"></textarea>
                <button type="submit">Оставить заявку</button>
            </div>
        </form>
    </div>

    {{-- Сообщение об успехе --}}
    @if(session('success'))
        <div style="display:none" id="lead-success" data-message="{{ session('success') }}"></div>
    @endif
@endsection
