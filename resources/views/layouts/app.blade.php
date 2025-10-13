<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $page->meta_title ?? $page->title ?? 'OrdaKlimat')</title>
    @if(!empty($page?->meta_description))
        <meta name="description" content="{{ $page->meta_description }}">
    @endif

    <link rel="shortcut icon" href="{{ asset('img/f-logo.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('head')
</head>
<body>
@include('layouts._header')
<div class="header-space"></div>

<main>
    @yield('content')
</main>

@include('layouts._footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
