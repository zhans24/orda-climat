{{-- resources/views/pages/about.blade.php --}}
@extends('layouts.app')

@section('title', $page->meta_title ?: ($page->title ?? 'О компании'))

@section('content')
    @php

        $rawAbout = $page->section('about_text');

        $tiptapToHtml = function ($node): string {
            if (is_string($node)) {
                return $node;
            }

            if (! is_array($node)) {
                return '';
            }

            if (($node['type'] ?? null) === 'doc' && is_array($node['content'] ?? null)) {
                $html = '';

                foreach ($node['content'] as $block) {
                    $type = $block['type'] ?? '';

                    if ($type === 'paragraph') {
                        $p = '';
                        foreach (($block['content'] ?? []) as $child) {
                            $ct = $child['type'] ?? '';
                            if ($ct === 'text') {
                                $text = $child['text'] ?? '';
                                $p .= e($text);
                            } elseif ($ct === 'hardBreak') {
                                $p .= '<br>';
                            }
                        }
                        if ($p !== '') {
                            $html .= '<p>' . $p . '</p>';
                        }
                    }

                    // Заголовки
                    if ($type === 'heading') {
                        $level = (int)($block['attrs']['level'] ?? 2);
                        $level = max(1, min(6, $level));
                        $t = '';
                        foreach (($block['content'] ?? []) as $child) {
                            if (($child['type'] ?? '') === 'text') {
                                $t .= e($child['text'] ?? '');
                            }
                        }
                        if ($t !== '') {
                            $html .= "<h{$level}>{$t}</h{$level}>";
                        }
                    }

                    // Списки
                    if (in_array($type, ['bulletList', 'orderedList'], true)) {
                        $tag = $type === 'bulletList' ? 'ul' : 'ol';
                        $html .= "<{$tag}>";
                        foreach (($block['content'] ?? []) as $li) {
                            if (($li['type'] ?? '') === 'listItem') {
                                $itemHtml = '';
                                foreach (($li['content'] ?? []) as $liChild) {
                                    $itemHtml .= $thisHtml = (function($n) use (&$tiptapToHtml) {
                                        return $tiptapToHtml(['type' => 'doc', 'content' => [$n]]);
                                    })($liChild);
                                }
                                $html .= "<li>{$itemHtml}</li>";
                            }
                        }
                        $html .= "</{$tag}>";
                    }

                    if ($type === 'blockquote') {
                        $inner = '';
                        foreach (($block['content'] ?? []) as $child) {
                            $inner .= $tiptapToHtml(['type' => 'doc', 'content' => [$child]]);
                        }
                        if ($inner !== '') {
                            $html .= "<blockquote>{$inner}</blockquote>";
                        }
                    }
                }

                return $html;
            }

            if (isset($node['content']) && is_string($node['content'])) {
                return $node['content'];
            }

            return '';
        };

        $aboutHtml  = $tiptapToHtml($rawAbout);

        // Медиа:
        $aboutImage = $page->about_image_url ?? null;
        $certs      = $page->getMedia('about_certificates') ?? collect();
        $clients    = $page->getMedia('about_clients') ?? collect();
    @endphp

    {{-- Хлебные крошки --}}
    @if(!empty($breadcrumbs))
        @include('layouts._breadcrumbs', ['items' => $breadcrumbs])
    @endif
    {{-- Блок "О компании" --}}
    <section class="about">
        <div class="container">
            <div class="about__container">
                <div class="about__left">
                    <h1 class="about__title title">{{ $page->title ?? 'О компании' }}</h1>
                    <div class="about__text">
                        {!! $aboutHtml !!}
                    </div>
                </div>

                <div class="about__right">
                    <div class="about__image">
                        @if ($aboutImage)
                            <img src="{{ $aboutImage }}" alt="{{ $page->title ?? 'О компании' }}">
                        @endif
                    </div>

                    <div class="about__socials">
                        <span>Социальные сети</span>

                        {{-- Instagram --}}
                        @php $hasInsta = !empty($instaHref ?? ''); @endphp
                        <a
                            @if($hasInsta)
                                href="{{ $instaHref }}" target="_blank" rel="noopener"
                            @else
                                aria-disabled="true" tabindex="-1" onclick="return false"
                            @endif
                        >
                            <!-- SVG Instagram -->
                            <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="17.5" cy="17.5" r="17.5" fill="#FF6600"/>
                                <path d="M21.7441 7C25.1936 7.00008 27.9999 9.80639 28 13.2559V21.7441C27.9999 25.1936 25.1936 27.9999 21.7441 28H13.2559C9.80641 27.9999 7.00009 25.1936 7 21.7441V13.2559C7.00008 9.80639 9.8064 7.00008 13.2559 7H21.7441ZM13.2559 9.1123C10.9676 9.1123 9.11239 10.9676 9.1123 13.2559V21.7441C9.11238 24.0324 10.9676 25.8877 13.2559 25.8877H21.7441C24.0322 25.8875 25.8866 24.0322 25.8867 21.7441V13.2559C25.8866 10.9677 24.0322 9.11255 21.7441 9.1123H13.2559ZM17.4307 12.0068C20.4214 12.0068 22.8545 14.4399 22.8545 17.4307C22.8544 20.4213 20.4213 22.8545 17.4307 22.8545C14.44 22.8545 12.007 20.4212 12.0068 17.4307C12.0068 14.44 14.44 12.0068 17.4307 12.0068ZM17.4297 14.1162C15.5994 14.1163 14.1162 15.6003 14.1162 17.4307C14.1163 19.2609 15.5995 20.745 17.4297 20.7451C19.26 20.7451 20.744 19.2609 20.7441 17.4307C20.7441 15.6003 19.2601 14.1162 17.4297 14.1162ZM22.9248 10.7549C23.6543 10.7551 24.2461 11.3466 24.2461 12.0762C24.2461 12.8057 23.6543 13.3973 22.9248 13.3975C22.1951 13.3975 21.6035 12.8058 21.6035 12.0762C21.6035 11.3465 22.1951 10.7549 22.9248 10.7549Z" fill="white"/>
                            </svg>
                        </a>

                        {{-- WhatsApp --}}
                        @php $hasWa = !empty($waHref ?? ''); @endphp
                        <a
                            @if($hasWa)
                                href="{{ $waHref }}" target="_blank" rel="noopener"
                            @else
                                aria-disabled="true" tabindex="-1" onclick="return false"
                            @endif
                        >
                            <!-- SVG WhatsApp -->

                            <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="17.5" cy="17.5" r="17.5" fill="#FF6600"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M24.8094 10.0518C22.8497 8.08486 20.2435 7.00111 17.4669 7C11.7457 7 7.08932 11.6678 7.08703 17.4053C7.08627 19.2393 7.56425 21.0296 8.47254 22.6076L7 28L12.5025 26.553C14.0186 27.382 15.7255 27.8188 17.4627 27.8196H17.467C23.1876 27.8196 27.8444 23.1512 27.8467 17.4136C27.8478 14.6331 26.7692 12.0187 24.8094 10.0518ZM17.4663 26.062H17.4628C15.9147 26.0614 14.3964 25.6445 13.0718 24.8565L12.7567 24.669L9.49151 25.5277L10.3631 22.3361L10.1579 22.0089C9.29432 20.6318 8.83819 19.0401 8.83887 17.4059C8.84076 12.6372 12.711 8.75748 17.4697 8.75748C19.774 8.75836 21.9402 9.65919 23.569 11.294C25.1979 12.9288 26.0944 15.1019 26.0935 17.4129C26.0916 22.182 22.2214 26.062 17.4663 26.062ZM20.4278 18.7385C20.6656 18.8252 21.9407 19.4543 22.2001 19.5844C22.2507 19.6098 22.298 19.6327 22.3418 19.654C22.5228 19.7416 22.645 19.8008 22.6971 19.8881C22.762 19.9966 22.762 20.5172 22.5458 21.1245C22.3297 21.7319 21.2935 22.2863 20.7952 22.3609C20.3484 22.4278 19.783 22.4558 19.1616 22.2579C18.785 22.138 18.3019 21.9781 17.6831 21.7102C15.252 20.6578 13.609 18.2956 13.2982 17.8487C13.2764 17.8174 13.2611 17.7955 13.2526 17.7841L13.2504 17.7812C13.113 17.5973 12.1936 16.3673 12.1936 15.0944C12.1936 13.8967 12.7804 13.2689 13.0505 12.98C13.069 12.9602 13.0861 12.942 13.1013 12.9253C13.339 12.665 13.62 12.5999 13.7929 12.5999C13.9658 12.5999 14.1389 12.6015 14.29 12.6091C14.3086 12.61 14.328 12.6099 14.348 12.6098C14.4991 12.6088 14.6876 12.6077 14.8735 13.0554C14.9453 13.2283 15.0504 13.4849 15.1612 13.7553C15.3841 14.2992 15.6299 14.899 15.6732 14.986C15.738 15.1161 15.7812 15.2679 15.6948 15.4415C15.6819 15.4674 15.6699 15.4918 15.6585 15.5152C15.5935 15.6483 15.5458 15.7461 15.4355 15.8753C15.3922 15.9259 15.3475 15.9804 15.3029 16.035C15.2135 16.1441 15.1242 16.2531 15.0464 16.3308C14.9166 16.4604 14.7814 16.6012 14.9327 16.8615C15.084 17.1217 15.6045 17.9733 16.3755 18.6627C17.2043 19.4038 17.9246 19.717 18.2897 19.8758C18.361 19.9068 18.4188 19.9319 18.4611 19.9532C18.7205 20.0834 18.8718 20.0616 19.0231 19.8881C19.1744 19.7146 19.6714 19.1289 19.8443 18.8686C20.0172 18.6083 20.1901 18.6517 20.4278 18.7385Z" fill="white"/>
                            </svg>
                        </a>

                        {{-- TikTok --}}
                        @php $hasTt = !empty($tiktokHref ?? ''); @endphp
                        <a
                            @if($hasTt)
                                href="{{ $tiktokHref }}" target="_blank" rel="noopener"
                            @else
                                aria-disabled="true" tabindex="-1" onclick="return false"
                            @endif
                        >
                            <!-- SVG TikTok -->
                            <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="17.5" cy="17.5" r="17.5" fill="#FF6600"/>
                                <path d="M18.3218 7.01751C19.4667 7 20.6032 7.0091 21.739 7C21.7768 8.42236 22.3531 9.70256 23.27 10.6508L23.2686 10.6494C24.2554 11.5395 25.5395 12.1166 26.9551 12.2146L26.9747 12.216V15.7429C25.6374 15.7093 24.3799 15.4004 23.2462 14.8696L23.3035 14.8934C22.7552 14.6294 22.2915 14.3583 21.8509 14.0558L21.8873 14.0796C21.8789 16.6351 21.8957 19.1906 21.8698 21.737C21.7978 23.0347 21.3669 24.2182 20.6759 25.2064L20.6899 25.1847C19.5345 26.8417 17.6629 27.9293 15.5347 27.9937H15.5249C15.4389 27.9979 15.3374 28 15.2353 28C14.0254 28 12.8945 27.6624 11.9307 27.0763L11.9587 27.0924C10.2046 26.0356 8.99464 24.2273 8.77084 22.1243L8.76804 22.0955C8.75055 21.6578 8.74216 21.2201 8.75965 20.7915C9.10235 17.4447 11.9027 14.8556 15.3067 14.8556C15.6892 14.8556 16.0641 14.8885 16.4285 14.9508L16.3893 14.9452C16.4068 16.2401 16.3544 17.5357 16.3544 18.8306C16.0585 18.7235 15.7172 18.6611 15.3612 18.6611C14.0548 18.6611 12.9434 19.4973 12.5322 20.6648L12.5259 20.6858C12.4329 20.9848 12.379 21.3287 12.379 21.6845C12.379 21.8287 12.3881 21.9716 12.4049 22.1117L12.4035 22.0948C12.6357 23.5277 13.8624 24.609 15.3416 24.609C15.3843 24.609 15.4263 24.6083 15.4682 24.6062H15.4619C16.4852 24.5754 17.3734 24.0242 17.8756 23.2098L17.8825 23.1972C18.0693 22.9366 18.1973 22.6215 18.2399 22.279L18.2406 22.2692C18.3281 20.7026 18.2931 19.1451 18.3015 17.5784C18.3099 14.0516 18.2931 10.5331 18.319 7.01541L18.3218 7.01751Z" fill="white"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Слайдер "Сертификаты/Документы компании" --}}
    @if ($certs->isNotEmpty())
        <section class="doc">
            <div class="container">
                <div class="doc__container">
                    <div class="doc__header">
                        <h2 class="doc__title title">Сертификат/Документы компании</h2>
                        <div class="doc__arrows">
                            <a href="javascript:;" class="doc__arrow prev" aria-label="Предыдущий">
                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <circle cx="25" cy="25" r="25" transform="matrix(-1 0 0 1 50 0)" fill="#FFEEE2"/>
                                    <path d="M30.6191 32L25.2885 25.7809C24.9033 25.3316 24.9033 24.6684 25.2885 24.2191L30.6191 18" stroke="#FF6600" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </a>
                            <a href="javascript:;" class="doc__arrow next" aria-label="Следующий">
                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <circle cx="25" cy="25" r="25" fill="#FFEEE2"/>
                                    <path d="M19.3809 32L24.7115 25.7809C25.0967 25.3316 25.0967 24.6684 24.7115 24.2191L19.3809 18" stroke="#FF6600" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="doc__slider swiper">
                        <div class="swiper-wrapper">
                            @foreach ($certs as $media)
                                <div class="doc__slider-item swiper-slide">
                                    <img
                                        src="{{ $media->getUrl('webp') ?: $media->getUrl() }}"
                                        alt="{{ $media->getCustomProperty('alt') ?? 'Документ' }}"
                                        loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Слайдер "Наши клиенты" --}}
    @if ($clients->isNotEmpty())
        <section class="client">
            <h2 class="client__title title">Наши клиенты</h2>
            <div class="client__slider swiper">
                <div class="swiper-wrapper">
                    @foreach ($clients as $client)
                        <div class="client__slider-item swiper-slide">
                            <img
                                src="{{ $client->getUrl('webp') ?: $client->getUrl() }}"
                                alt="{{ $client->getCustomProperty('alt') ?? 'Клиент' }}"
                                loading="lazy">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
