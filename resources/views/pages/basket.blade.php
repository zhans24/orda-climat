@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
    @php
        use App\Models\Page;
        $deliveryFlat = Page::cartDeliveryPriceGlobal(5000);
        $showDelivery = Page::cartShowDeliveryOption(true);
    @endphp

    <section class="breadcrumbs">
        <div class="container">
            <div class="breadcrumbs__container">
                <ul class="breadcrumbs__list">
                    <li class="breadcrumbs__list-item">
                        <a href="{{ route('home') }}" class="breadcrumbs__list-link">Главная</a>
                    </li>
                    <li class="breadcrumbs__list-item">
                        <span class="breadcrumbs__list-link active">Корзина</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="basket">
        <div class="container">
            <div class="section__container">
                <h1 class="basket__title title">Корзина</h1>

                <div class="basket__wrapper">
                    <table class="basket__table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Товар</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Монтаж</th>
                            <th>Итого</th>
                        </tr>
                        </thead>
                        <tbody class="js-cart">
                        {{-- Пример строки (оставлен как комментарий, генерится JS)
                        <tr data-id="1" class="busket__item">
                            <td>
                                <div class="basket__image"><img src="{{ asset('img/product-2.png') }}" alt=""></div>
                            </td>
                            <td>
                                <div class="basket__desc">
                                    <div class="basket__name">Product 1</div>
                                    <div class="basket__art">Арт: Articul 1</div>
                                </div>
                            </td>
                            <td><div class="basket__price">10 000 Т</div></td>
                            <td>
                                <div class="basket__counter">
                                    <button type="button" class="js-minus">
                                        <svg width="6" height="2" viewBox="0 0 6 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 1.33333V0H6V1.33333H0Z" fill="#FF6600"/>
                                        </svg>
                                    </button>
                                    <span class="js-cart-item-quantity">1</span>
                                    <button type="button" class="js-plus">
                                        <svg width="7" height="8" viewBox="0 0 7 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.80023 7.25336V4.72003H0.240234V3.22669H2.80023V0.693359H4.32023V3.22669H6.88024V4.72003H4.32023V7.25336H2.80023Z" fill="#FF6600"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="basket__montage">
                                    <input type="checkbox" class="js-montage" name="montage">
                                </div>
                            </td>
                            <td>
                                <div class="basket__totalprice js-cart-item-totalprice"><span>10 000</span> Т</div>
                            </td>
                        </tr>
                        --}}
                        </tbody>
                    </table>
                </div>

                {{-- Форма заказа (не менял селекторы, только names и скрытые поля) --}}
                <form action="{{ route('orders.store') }}" method="post" onsubmit="return submitOrderFromLS(event)">
                    @csrf
                    <div class="basket__form">
                        <div class="basket__top">
                            <div class="basket__form-block">
                                <div class="basket__form-title">Выберите</div>
                                <div class="basket__form-labels">
                                    <label for="label-1" class="basket__form-label">
                                        <input type="radio" id="label-1" name="face" value="person" checked>
                                        <span>Физическое лицо</span>
                                    </label>
                                    <label for="label-2" class="basket__form-label">
                                        <input type="radio" id="label-2" name="face" value="company">
                                        <span>Юридическое лицо</span>
                                    </label>
                                </div>
                            </div>

                            <div class="basket__form-block">
                                <div class="basket__form-title">Способ доставки</div>
                                <div class="basket__form-labels">
                                    <label for="label-3" class="basket__form-label">
                                        <input type="radio" id="label-3" value="0" name="delivery" class="js-radio-delivery" checked>
                                        <span>Самовывоз</span>
                                    </label>

                                    @if($showDelivery)
                                        <label for="label-4" class="basket__form-label">
                                            <input type="radio" id="label-4" value="1" name="delivery"
                                                   class="js-radio-delivery js-deliv-point"
                                                   data-price="{{ $deliveryFlat }}">
                                            <span>Доставка</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="basket__bot">
                            <div class="basket__form-block">
                                <div class="basket__form-title">Контактное лицо</div>
                                <div class="basket__form-fields">
                                    <div class="basket__form-field">
                                        <span>Имя</span>
                                        <input type="text" name="customer_name" placeholder="Динара" required>
                                    </div>
                                    <div class="basket__form-field">
                                        <span>Фамилия</span>
                                        <input type="text" name="customer_surname" placeholder="Адилова">
                                    </div>
                                    <div class="basket__form-field">
                                        <span>Телефон</span>
                                        <input type="tel" name="customer_phone" placeholder="+ 747 123 45 67" required>
                                    </div>
                                    <div class="basket__form-field">
                                        <span>Email</span>
                                        <input type="email" name="customer_email" placeholder="mail@example.com">
                                    </div>
                                    <div class="basket__form-field">
                                        <span>Город</span>
                                        <input type="text" name="city" placeholder="Астана">
                                    </div>
                                    <div class="basket__form-field">
                                        <span>Адрес</span>
                                        <input type="text" name="address" placeholder="Макатаева 314к3">
                                    </div>
                                    <div class="basket__form-field">
                                        <span>Сообщение</span>
                                        <textarea name="message" placeholder="">Сообщение</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="basket__req">
                                <div class="basket__req-title">Ваш заказ</div>

                                <div class="basket__req-row">
                                    <span>Сумма заказа</span>
                                    {{-- Оставил структуру как у твоей верстки (без вложенного span) --}}
                                    <span class="js-cart-total-summa" data-summ="0">0 Т</span>
                                </div>

                                <div class="basket__req-row js-basket-deliv" data-price-deliv="5000">
                                    <span>Доставка</span>
                                    <span class="deliv">Самовывоз</span>
                                </div>

                                <div class="basket__req-total">
                                    <span>ИТОГО</span>
                                    <span class="js-all-total-price">0 Т</span>
                                </div>

                                <button class="basket__submit" type="submit">Отправить</button>
                            </div>
                        </div>
                    </div>

                    {{-- служебные скрытые поля для backend --}}
                    <input type="hidden" name="delivery_price" class="js-delivery-price" value="0">
                    <input type="hidden" name="items_json" class="js-items-json">
                </form>

                {{-- Тех. поле для HTML-таблицы (не трогал класс) --}}
                <textarea hidden class="textarea-table"></textarea>

                <div class="empty" style="display:none">В вашей корзине пусто</div>
            </div>
        </div>
    </section>
@endsection
