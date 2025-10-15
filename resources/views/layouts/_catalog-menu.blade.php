@php
    use App\Models\Category;

    $catalogs = Category::query()
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->with([
            'media',
            'children' => fn ($q) => $q
                ->where('is_active', true)
                ->orderBy('position')
                ->withCount('products')
                ->with('media')
        ])
        ->orderBy('position')
        ->get();
@endphp

<div class="menu" id="catalog-menu">
    <div class="menu__list">
        {{-- Режим "Все категории" — показывает всё справа --}}
        <a href="javascript:;" class="menu__list-item reset">
            Все категории
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M7.035 14.25L11.033 9.586a1 1 0 0 0 0-1.172L7.035 3.75"
                      stroke="#2B308B" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </a>

        {{-- Левый столбец категорий: не навигируем, только фильтруем по data-id --}}
        @foreach($catalogs as $cat)
            <a href="javascript:;"
               class="menu__list-item"
               data-id="{{ $cat->slug }}">
                {{ $cat->name }}
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M7.035 14.25L11.033 9.586a1 1 0 0 0 0-1.172L7.035 3.75"
                          stroke="#2B308B" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </a>
        @endforeach
    </div>

    <div class="menu__block">
        <a href="javascript:;" class="menu__back">Назад</a>

        <div class="menu__items">
            {{-- Правый столбец: кликабельно уводит на страницу подкатегории --}}
            @foreach($catalogs as $cat)
                @foreach(($cat->children ?? []) as $category)
                    <a href="{{ route('category.show', $category->slug) }}"
                       class="menu__item"
                       data-id="{{ $cat->slug }}">
                        <div class="menu__item-col">
                            <div class="menu__item-title">{{ $category->name }}</div>
                            <div class="menu__item-count">
                                {{ $category->products_count ?? '—' }} товаров
                            </div>
                        </div>
                        <div class="menu__item-img">
                            <img
                                src="{{ $category->image_url ?? $cat->image_url }}"
                                alt="{{ $category->name }}">
                        </div>
                    </a>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
