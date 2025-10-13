<?php

namespace App\Filament\Resources\Slides\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\{
    TextColumn,
    SpatieMediaLibraryImageColumn,
    ToggleColumn
};
use Filament\Tables\Filters\TernaryFilter;

class SlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->columns([
                SpatieMediaLibraryImageColumn::make('slide_image')
                    ->collection('slide_image')
                    ->conversion('webp')
                    ->square()
                    ->width(100)
                    ->height(100)
                    ->label('Фото'),

                TextColumn::make('slider.name')
                    ->label('Слайдер')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Заголовок')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('position')
                    ->label('Позиция')
                    ->numeric()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Активен')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Активность')
                    ->trueLabel('Только активные')
                    ->falseLabel('Выключенные')
                    ->placeholder('Все'),
            ])
            ->recordActions([
                EditAction::make()->label('Редактировать'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}
