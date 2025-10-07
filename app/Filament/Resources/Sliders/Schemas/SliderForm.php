<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Заголовок'),
            TextInput::make('subtitle')->label('Подзаголовок'),
            TextInput::make('link')->label('Ссылка'),
            TextInput::make('position')->numeric()->default(0),
            Toggle::make('is_active')->label('Активен')->default(true),

            ])->columns(2);
    }
}
