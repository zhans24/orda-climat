<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Orders\Schemas\OrderForm;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->persistFiltersInSession()
            ->columns([
                TextColumn::make('number')
                    ->label('№')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Скопировано'),

                TextColumn::make('customer_name')
                    ->label('Клиент')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('customer_phone')
                    ->label('Телефон')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('shipping_address')
                    ->label('Адрес')
                    ->getStateUsing(fn ($record) => trim(
                        implode(', ', array_filter([
                            data_get($record->shipping_address, 'city'),
                            data_get($record->shipping_address, 'address'),
                        ]))
                    ))
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('delivery_method')
                    ->label('Доставка')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'delivery' ? 'Доставка' : 'Самовывоз')
                    ->colors([
                        'success' => 'pickup',
                        'info'    => 'delivery',
                    ])
                    ->sortable(),

                // новый столбец: стоимость доставки
                TextColumn::make('delivery_price')
                    ->label('₸ Доставка')
                    ->getStateUsing(fn ($record) => (float) data_get($record->shipping_address, 'delivery_price', 0))
                    ->money('KZT', true)
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->colors([
                        'warning' => 'new',
                        'info'    => 'processing',
                        'primary' => 'paid',
                        'indigo'  => 'picking',
                        'cyan'    => 'shipped',
                        'purple'  => 'installed',
                        'success' => 'closed',
                        'danger'  => 'canceled',
                    ])
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Сумма')
                    ->money('KZT', true)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(OrderForm::STATUSES),

                Filter::make('total_range')
                    ->label('Сумма (от/до)')
                    ->form([
                        TextInput::make('min')->numeric()->label('Мин'),
                        TextInput::make('max')->numeric()->label('Макс'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (($data['min'] ?? null) !== null && $data['min'] !== '') {
                            $query->where('total', '>=', $data['min']);
                        }
                        if (($data['max'] ?? null) !== null && $data['max'] !== '') {
                            $query->where('total', '<=', $data['max']);
                        }
                        return $query;
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $min = $data['min'] ?? null;
                        $max = $data['max'] ?? null;
                        return ($min || $max)
                            ? 'Сумма: ' . ($min ? "от {$min}" : '') . ($max ? " до {$max}" : '')
                            : null;
                    }),

                Filter::make('created_between')
                    ->label('Дата')
                    ->form([
                        DatePicker::make('from')->label('С'),
                        DatePicker::make('to')->label('По'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['from'])) {
                            $query->whereDate('created_at', '>=', $data['from']);
                        }
                        if (!empty($data['to'])) {
                            $query->whereDate('created_at', '<=', $data['to']);
                        }
                        return $query;
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $from = $data['from'] ?? null;
                        $to = $data['to'] ?? null;
                        if (!$from && !$to) return null;
                        return 'Дата: ' . ($from ? "с {$from}" : '') . ($to ? " по {$to}" : '');
                    }),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('to_processing')
                    ->label('В обработку')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'new')
                    ->action(fn ($record) => $record->update(['status' => 'processing'])),

                Action::make('mark_done')
                    ->label('Закрыть')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => in_array($record->status, ['new','processing','picking','installed']))
                    ->action(fn ($record) => $record->update(['status' => 'closed'])),

                Action::make('cancel')
                    ->label('Отменить')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => ! in_array($record->status, ['canceled','closed']))
                    ->action(fn ($record) => $record->update(['status' => 'canceled'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
