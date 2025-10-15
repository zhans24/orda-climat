<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, IconColumn, BadgeColumn};
use Filament\Actions\EditAction;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'asc')
            ->columns([
                TextColumn::make('title')->label('Название страницы')->sortable()->searchable(),
                TextColumn::make('updated_at')->label('Обновлено')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->recordActions([
                EditAction::make()->label('Изменить данные'),
            ])
            ->filters([])
            ->paginated(false);
    }
}
