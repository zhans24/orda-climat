<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class OrderForm
{
    public const STATUSES = [
        'new'        => 'Новый',
        'processing' => 'В обработке',
        'paid'       => 'Оплачен',
        'picking'    => 'Сборка',
        'shipped'    => 'Отправлен',
        'installed'  => 'Установлен',
        'closed'     => 'Закрыт',
        'canceled'   => 'Отменён',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->schema([
                TextInput::make('number')
                    ->label('№ заказа')
                    ->readOnly()
                    ->helperText('Генерируется автоматически'),

                Select::make('status')
                    ->label('Статус')
                    ->options(self::STATUSES)
                    ->required()
                    ->native(false),

                TextInput::make('total')
                    ->label('Итого')
                    ->prefix('₸')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(false)
                    ->formatStateUsing(fn (?Order $record) => (string) ($record?->total ?? 0)),
            ]),

            Section::make('Клиент')->schema([
                Grid::make(3)->schema([
                    TextInput::make('customer_name')->label('Клиент')->maxLength(255),
                    TextInput::make('customer_phone')->label('Телефон')->tel()->maxLength(30),
                    TextInput::make('customer_email')->label('Email')->email()->maxLength(255),
                ]),
            ]),

            Section::make('Адрес / Доставка')->schema([
                // способ доставки + цена
                Grid::make(2)->schema([
                    Select::make('shipping_address.delivery_method')
                        ->label('Способ доставки')
                        ->options([
                            'pickup'   => 'Самовывоз',
                            'delivery' => 'Доставка',
                        ])
                        ->default('pickup')
                        ->native(false),

                    TextInput::make('shipping_address.delivery_price')
                        ->label('Стоимость доставки')
                        ->prefix('₸')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->visible(fn (Get $get) => $get('shipping_address.delivery_method') === 'delivery'),
                ]),

                // город + один строковый адрес
                Grid::make(2)->schema([
                    TextInput::make('shipping_address.city')
                        ->label('Город')
                        ->maxLength(255),

                    TextInput::make('shipping_address.address')
                        ->label('Адрес')
                        ->placeholder('Пример: Макатаева 134, кв. 1')
                        ->maxLength(500),
                ]),
            ]),
        ])->columns(1);
    }
}
