<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

            TextInput::make('total')->label('Итого')->numeric()->prefix('₸')->readOnly(),

            TextInput::make('customer_name')->label('Клиент'),
            TextInput::make('customer_phone')->label('Телефон'),
            TextInput::make('customer_email')->label('Email')->email(),

            KeyValue::make('shipping_address')->label('Адрес доставки')
                ->keyLabel('поле')->valueLabel('значение')
                ->helperText('Пример: city, street, apt, note'),
        ])->columns(3);
    }
}
