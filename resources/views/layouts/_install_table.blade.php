@php
    /** @var array<int, array{service?:string, model?:string, price?:string}> $rows */
    $rows = $rows ?? [];
@endphp

<table class="install__table">
    <thead>
    <tr>
        <th>Услуга</th>
        <th>{{ $modelColTitle ?? 'Модель' }}</th>
        <th>Цена</th>
    </tr>
    </thead>
    <tbody>
    @forelse($rows as $r)
        <tr>
            <td>{!! nl2br(e($r['service'] ?? '—')) !!}</td>
            <td>{!! nl2br(e($r['model']   ?? '—')) !!}</td>
            <td>{!! nl2br(e($r['price']   ?? '—')) !!}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3">Данные появятся позже.</td>
        </tr>
    @endforelse
    </tbody>
</table>
