<header class="header">
    {{-- верхняя полоска --}}
    <div class="header__top">
        <div class="container">
            <div class="header__top-wrapper">
                <a href="{{ $emailHref ?: '#' }}" target="_blank">
                    {{ $contacts->email ?? 'Не установлено' }}
                </a>
                    <a href="{{ $phoneHref ? 'tel:' . $phoneHref : '#' }}">
                    {{ $contacts->phone ?? 'Не установлено' }}
                </a>
            </div>
        </div>
    </div>

    {{-- логотип, меню, корзина --}}
    <div class="header__bot">
        <div class="container">
            <div class="header__bot-container">
                <a href="{{ route('home') }}" class="header__logo">
                    <img src="{{ asset('img/logo.png') }}" alt="OrdaKlimat">
                </a>

                <button class="header__catalog">
                    <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="18" height="2" rx="1" fill="white"/>
                        <rect y="5" width="18" height="2" rx="1" fill="white"/>
                        <rect y="10" width="18" height="2" rx="1" fill="white"/>
                    </svg>
                    Каталог
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.75 6.75L8.41432 10.7479C8.7513 11.0368 9.2487 11.0368 9.58567 10.7479L14.25 6.75" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>

                <nav class="header__nav">
                    <ul class="header__nav-list">
                        <li class="header__nav-item">
                            <a class="header__nav-link" href="{{ route('about') }}">О компании</a>
                        </li>
                        <li class="header__nav-item">
                            <a class="header__nav-link" href="{{ route('delivery') }}">Доставка/Оплата</a>
                        </li>
                        <li class="header__nav-item">
                            <a class="header__nav-link" href="{{ route('install.ac') }}">Установка кондиционеров</a>
                        </li>
                        <li class="header__nav-item">
                            <a class="header__nav-link" href="{{ route('install.vent') }}">Установка вентиляции</a>
                        </li>
                        <li class="header__nav-item">
                            <a class="header__nav-link" href="{{ route('contacts') }}">Контакты</a>
                        </li>
                    </ul>
                </nav>

                <a href="#" class="header__basket">
                    <img src="{{ asset('img/basket-icon.png') }}" alt="">
                    <span>{{ session('cart.count', 0) }}</span>
                </a>

                {{-- бургер для мобилок: 9 точек и линии как в верстке --}}
                <div class="burger" id="menu-icon">
                    <div class="burger__dot burger__dot--line burger__dot--left-top"></div>
                    <div class="burger__dot burger__dot--aside"></div>
                    <div class="burger__dot burger__dot--line burger__dot--right-top"></div>
                    <div class="burger__dot burger__dot--aside"></div>
                    <div class="burger__dot burger__dot--aside"></div>
                    <div class="burger__dot burger__dot--aside"></div>
                    <div class="burger__dot burger__dot--line burger__dot--left-bottom"></div>
                    <div class="burger__dot burger__dot--aside"></div>
                    <div class="burger__dot burger__dot--line burger__dot--right-bottom"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- выпадающее меню каталога (левый сайдбар) --}}
    @include('layouts._catalog-menu')
</header>
