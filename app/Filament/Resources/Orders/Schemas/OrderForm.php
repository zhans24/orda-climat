<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('number')->label('№ заказа')->readOnly()->helperText('Генерируется автоматически'),
            Select::make('status')->options([
                'new'       => 'Новый',
                'paid'      => 'Оплачен',
                'picking'   => 'Сборка',
                'shipped'   => 'Отправлен',
                'installed' => 'Установлен',
                'closed'    => 'Закрыт',
                'canceled'  => 'Отменён',
            ])->required(),


            TextInput::make('total')
                ->label('Итого')
                ->numeric()
                ->prefix('₸')
                ->readOnly()
                ->dehydrated(false)
                ->formatStateUsing(function (?Order $record) {
                    return $record?->total ?? 0;
                }),

            TextInput::make('customer_name')->label('Клиент'),
            TextInput::make('customer_phone')->label('Телефон'),
            TextInput::make('customer_email')->label('Email')->email(),

            Section::make('Адрес доставки')
                ->schema([
                    TextInput::make('city')->label('Город')->required(),
                    TextInput::make('street')->label('Улица')->required(),
                    TextInput::make('house')->label('Дом/корпус'),
                    TextInput::make('apartment')->label('Кв./офис'),
                    TextInput::make('postcode')->label('Индекс')->mask('999999')->placeholder('050000'),
                    TextInput::make('contact')->label('Контактное лицо'),
                    TextInput::make('note')->label('Примечание')->columnSpanFull(),
                ])
                ->columns(2)
                ->statePath('shipping_address')

        ])->columns(3);
    }
}
