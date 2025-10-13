@extends('layouts.app')
@section('title', $page?->meta_title ?? $product->name)

@section('content')
    <div class="container">
        <h1>{{ $product->name }}</h1>

        <div class="product">
            <div class="product__gallery">
                @php $cover = $product->getFirstMediaUrl('cover','webp') ?: $product->getFirstMediaUrl('cover'); @endphp
                @if($cover)
                    <img src="{{ $cover }}" alt="{{ $product->name }}">
                @endif
            </div>
            <div class="product__info">
                <div class="product__price">{{ number_format($product->price, 2, ',', ' ') }} ₸</div>
                @if($product->short)<p>{{ $product->short }}</p>@endif
                {!! $product->description !!}
            </div>
        </div>
    </div>
@endsection
