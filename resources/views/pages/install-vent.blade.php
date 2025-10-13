@extends('layouts.app')

@section('title', $page->meta_title ?: ($page->title ?: 'Установка вентиляции'))

@section('content')
    @php
        $intro     = (string) $page->section('intro_text', '');
        $blockH    = (string) $page->section('block_title', 'Установка вентиляции');
        $blockTx   = (string) $page->section('block_text', '');
        $rules     = (string) $page->section('rules_text', '');
        $priceRows = $page->section('price_rows', []) ?: [];
    @endphp

    @include('layouts._breadcrumbs', ['items' => [
        ['title' => 'Главная', 'url' => route('home')],
        ['title' => $page->title ?: 'Установка вентиляции', 'active' => true],
    ]])

    <section class="install">
        <div class="container">
            <div class="section__container">
                <h2 class="install__title title">{{ $page->title ?: 'Установка вентиляции' }}</h2>
                <div class="install__text">
                @if(trim(strip_tags($intro)) !== '')
                    <div class="install__text">{!! $intro !!}</div>
                @endif
                    <br>

                    <h3>{{ $blockH }}</h3>
                    @if(trim(strip_tags($blockTx)) !== '')
                        {!! $blockTx !!}
                    @endif

                    @include('layouts._install_table', [
                      'rows' => $priceRows,
                      'modelColTitle' => 'Модель вентиляции',
                    ])
                </div>

                @if(trim(strip_tags($rules)) !== '')
                    <div class="install__text">{!! $rules !!}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
