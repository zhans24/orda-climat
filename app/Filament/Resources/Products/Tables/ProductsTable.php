<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\{SpatieMediaLibraryImageColumn, TextColumn, IconColumn};
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->label('Фото')
                    ->collection('cover')
                    ->conversion('thumb')
                    ->circular(),
                TextColumn::make('name')->label('Название')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Категория')->sortable(),
                TextColumn::make('price')->label('Цена')->money('KZT', true),
                IconColumn::make('is_active')->label('Активен')->boolean(),
                IconColumn::make('is_available')->label('На главную')->boolean(),
                TextColumn::make('created_at')->label('Создан')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->recordActions([
                EditAction::make()->label('Ред.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}
