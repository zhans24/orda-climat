<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select,TextInput,Toggle,Textarea,RichEditor,KeyValue,SpatieMediaLibraryFileUpload};
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('category_id')->relationship('category','name')->searchable()->required(),
            TextInput::make('name')->required()->live(onBlur:true)
                ->afterStateUpdated(fn($state,$set)=>$set('slug', Str::slug($state))),
            TextInput::make('slug')->disabled()->dehydrated(false),
            TextInput::make('sku')->unique(ignoreRecord:true),
            TextInput::make('price')->numeric()->prefix('₸')->required(),
            Toggle::make('is_active')->label('Активен')->default(true),
            Textarea::make('short')->rows(3),
            RichEditor::make('description')->columnSpanFull(),
            KeyValue::make('attributes')->label('Атрибуты')->keyLabel('ключ')->valueLabel('значение')->columnSpanFull(),

            // Галерея (Spatie ML)
            SpatieMediaLibraryFileUpload::make('images')->collection('images')->multiple()->image()->imageEditor()->reorderable(),
        ])->columns(2);
    }
}
