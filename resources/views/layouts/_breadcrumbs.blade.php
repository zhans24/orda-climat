@pushOnce('styles')
    <link rel="stylesheet" href="{{ asset('css/breadcrumbs.css') }}">
@endPushOnce

@pushOnce('scripts')
    <script src="{{ asset('js/breadcrumbs.js') }}" defer></script>
@endPushOnce

@if(!empty($items))
    <section class="breadcrumbs">
        <div class="container">
            <div class="breadcrumbs__container">
                <ul class="breadcrumbs__list">
                    @foreach($items as $item)
                        <li class="breadcrumbs__list-item">
                            @if(!empty($item['url']) && empty($item['active']))
                                <a href="{{ $item['url'] }}" class="breadcrumbs__list-link">
                                    {{ $item['title'] }}
                                </a>
                            @else
                                <a href="javascript:;" class="breadcrumbs__list-link active">
                                    {{ $item['title'] }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
@endif
