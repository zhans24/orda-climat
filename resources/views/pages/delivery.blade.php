@extends('layouts.app')

@section('title', $page->meta_title ?: ($page->title ?: 'Доставка и оплата'))

@pushOnce('styles')
    <link rel="stylesheet" href="{{ asset('css/delivery.css') }}">
@endPushOnce

@section('content')
    @php
        $title   = $page->title ?: 'Доставка и оплата';

        // тексты из JSON content
        $intro   = $page->section('delivery_intro', '');
        $payT    = $page->section('delivery_pay_title', 'Способы оплаты');
        $payTx   = $page->section('delivery_pay_text', '');
        $retT    = $page->section('delivery_return_title', 'Возврат и отмена');
        $retTx   = $page->section('delivery_return_text', '');
        $secT    = $page->section('delivery_secure_title', 'Безопасность');
        $secTx   = $page->section('delivery_secure_text', '');

        // картинка справа (Media Library)
        $imageUrl = $page->getFirstMediaUrl('delivery_image', 'webp') ?: $page->getFirstMediaUrl('delivery_image');
    @endphp

    {{-- Хлебные крошки --}}
    @include('layouts._breadcrumbs', ['items' => [
        ['title' => 'Главная', 'url' => route('home')],
        ['title' => $title, 'active' => true],
    ]])

    <section class="about delivery">
        <div class="container">
            <div class="about__container">
                <div class="about__left">
                    <h1 class="about__title title">{{ $title }}</h1>

                    <div class="about__text">
                        {{-- интро абзац сверху --}}
                        @if(trim(strip_tags($intro)) !== '')
                            {!! $intro !!}
                        @endif

                        {{-- Способы оплаты --}}
                        <h3 class="delivery__subtitle">{{ $payT }}</h3>
                        @if(trim(strip_tags($payTx)) !== '')
                            {!! $payTx !!}
                        @endif

                        {{-- Возврат и отмена --}}
                        <h3 class="delivery__subtitle">{{ $retT }}</h3>
                        @if(trim(strip_tags($retTx)) !== '')
                            {!! $retTx !!}
                        @endif

                        {{-- Безопасность --}}
                        <h3 class="delivery__subtitle">{{ $secT }}</h3>
                        @if(trim(strip_tags($secTx)) !== '')
                            {!! $secTx !!}
                        @endif
                    </div>
                </div>

                <div class="about__right">
                    <div class="about__image delivery__image">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $title }}">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
