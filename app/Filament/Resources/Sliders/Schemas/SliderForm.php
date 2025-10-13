<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{TextInput, Toggle};

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Название')
                ->required()
                ->maxLength(255),

            TextInput::make('key')
                ->label('Ключ (для вызова на фронте)')
                ->helperText('Пример: home_hero, about_banner')
                ->unique('sliders', 'key', ignoreRecord: true)
                ->required()
                ->maxLength(255),

            Toggle::make('is_active')
                ->label('Активен')
                ->default(true)
                ->inline(false),
        ])->columns(2);
    }
}
