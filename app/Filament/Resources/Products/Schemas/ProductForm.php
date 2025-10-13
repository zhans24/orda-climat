<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Rules\LeafCategory;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{
    TextInput, Textarea, Toggle, Select, RichEditor
};
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Название')->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', str($state)->slug())),
            TextInput::make('slug')->label('Слаг')->required()->unique(ignoreRecord: true),

            Select::make('category_id')->label('Категория (лист)')
                ->required()->searchable()->preload()
                ->options(fn () => Category::query()
                    ->active()
                    ->defaultOrder() // важно: до leaves()
                    ->leaves()
                    ->pluck('name','id'))
                ->rules([new LeafCategory]),

            TextInput::make('sku')->label('Артикул'),
            TextInput::make('price')->label('Цена (₸)')->numeric()->minValue(0),

            Toggle::make('is_active')->label('Активен')->default(true),
            Toggle::make('is_available')->label('В наличии')->default(true),

            // Обложка товара
            SpatieMediaLibraryFileUpload::make('cover')
                ->label('Обложка')
                ->collection('cover')
                ->image()
                ->imageEditor()
                ->imageEditorAspectRatios(['4:3','1:1','16:9'])
                ->conversion('card')
                ->responsiveImages()
                ->downloadable(false)
                ->columnSpanFull(),

            // Галерея товара (мультия)
            SpatieMediaLibraryFileUpload::make('gallery')
                ->label('Галерея')
                ->collection('gallery')
                ->image()
                ->multiple()
                ->reorderable()
                ->panelLayout('grid')     // аккуратная сетка
                ->conversion('thumb')
                ->responsiveImages()
                ->downloadable(false)
                ->columnSpanFull(),

            Textarea::make('short')->label('Кратко'),
            RichEditor::make('description')->label('Описание')->columnSpanFull(),

            TextInput::make('meta_title')->label('Meta title'),
            Textarea::make('meta_description')->label('Meta description')->rows(3),
        ])->columns(2);
    }
}
