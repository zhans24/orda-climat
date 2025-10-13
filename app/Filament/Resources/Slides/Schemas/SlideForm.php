<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{
    Select,
    TextInput,
    Textarea,
    Toggle,
    SpatieMediaLibraryFileUpload
};

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('slider_id')
                ->label('Слайдер')
                ->relationship('slider', 'name')
                ->searchable()
                ->preload()
                ->required(),

            SpatieMediaLibraryFileUpload::make('slide_image')
                ->label('Изображение')
                ->collection('slide_image')
                ->conversion('webp')
                ->image()
                ->imageEditor()
                ->visibility('public')
                ->openable()
                ->downloadable()
                ->required(),

            TextInput::make('title')
                ->label('Заголовок')
                ->maxLength(255),

            Textarea::make('subtitle')
                ->label('Подзаголовок')
                ->rows(3)
                ->maxLength(1000),

            TextInput::make('button_text')
                ->label('Текст кнопки')
                ->maxLength(100),

            TextInput::make('button_link')
                ->label('Ссылка')
                ->placeholder('https://'),

            TextInput::make('position')
                ->label('Позиция')
                ->numeric()
                ->default(0)
                ->columnSpan(2),

            Toggle::make('is_active')
                ->label('Активен')
                ->default(true)
                ->inline(false)
                ->columnSpan(2),
        ])->columns(2);
    }
}
