@extends('layouts.app')

@section('title', $page->meta_title ?? 'Популярные категории')

@section('content')
    @if(!empty($breadcrumbs))
        @include('layouts._breadcrumbs', ['items' => $breadcrumbs])
    @endif

    <section class="popular">
        <div class="container">
            <div class="popular__container">
                <h2 class="popular__title title">{{ $page->title ?? 'Популярные категории' }}</h2>
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
                            @if(isset($cat->products_count))
                                <div class="popular__item-count">{{ $cat->products_count }} штук</div>
                            @endif
                        </a>
                    @empty
                        <p>Категории скоро появятся.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
