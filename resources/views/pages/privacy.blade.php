@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('content')
    @php
        // 1) Берём HTML из JSON: content.privacy_text
        // Filament RichEditor иногда отдаёт строку, иногда массив вида ['content' => '...'] — учитываем оба варианта.
        $privacyRaw  = $page->section('privacy_text', '');
        $privacyHtml = is_array($privacyRaw) ? ($privacyRaw['content'] ?? '') : (string) $privacyRaw;

        // 2) Заголовок
        $h1 = $page->title ?: 'Политика конфиденциальности';
    @endphp

    {{-- Хлебные крошки --}}
    @if(!empty($breadcrumbs))
        @include('layouts._breadcrumbs', ['items' => $breadcrumbs])
    @endif
    {{-- Контент политики --}}
    <section class="install">
        <div class="container">
            <div class="section__container">
                <h1 class="install__title title">{{ $h1 }}</h1>

                <div class="install__text text-content">
                    @if(trim(strip_tags($privacyHtml)) !== '')
                        {!! $privacyHtml !!}
                    @else
                        <p>Контент страницы появится позже.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
