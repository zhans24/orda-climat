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
            Select::make('category_id')
                ->label('Категория')
                ->relationship('category', 'name')
                ->searchable()->preload()->required(),
            Select::make('section_id')
                ->label('Секция')
                ->relationship('section', 'name')
                ->searchable()->preload(),
            TextInput::make('name')->required()->live(onBlur:true)
                ->afterStateUpdated(fn($state,$set)=>$set('slug', Str::slug($state))),
            TextInput::make('slug')
                ->rule('alpha_dash')
                ->unique(table: 'products', column: 'slug', ignoreRecord: true)
                ->helperText('Можно отредактировать вручную')
                ->afterStateUpdated(function ($state, $set) {
                    $set('slug_manually_edited', true);
                }),
            TextInput::make('code')->unique(ignoreRecord:true),
            TextInput::make('price')->numeric()->prefix('₸')->required(),
            Toggle::make('is_active')->label('Активен')->default(true),
            Textarea::make('short')->rows(3),
            RichEditor::make('description')->columnSpanFull(),
            KeyValue::make('attributes')->label('Атрибуты')->keyLabel('ключ')->valueLabel('значение')->columnSpanFull(),

            SpatieMediaLibraryFileUpload::make('images')->collection('images')->multiple()->image()->imageEditor()->reorderable(),
        ])->columns(2);
    }
}
