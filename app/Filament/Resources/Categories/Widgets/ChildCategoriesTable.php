<?php

namespace App\Filament\Resources\Categories\Widgets;

use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\{SpatieMediaLibraryImageColumn, TextColumn, IconColumn, ToggleColumn};
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Actions\Action;

class ChildCategoriesTable extends BaseWidget
{
    protected static ?string $heading = 'Дочерние категории';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Category::query()
                    ->whereNotNull('parent_id')
                    ->defaultOrder()
            )
            ->defaultSort('_lft')
            ->recordUrl(fn (Category $record) => CategoryResource::getUrl('edit', ['record' => $record]))
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->label('Фото')
                    ->collection('cover')
                    ->conversion('thumb') ,
        TextColumn::make('name')->label('Название')->searchable()->sortable(),
                TextColumn::make('parent.name')->label('Родитель')->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Активна')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark')
                    ->afterStateUpdated(fn (Category $record, bool $state) => $record->save()),

                ToggleColumn::make('is_popular')
                    ->label('Популярная')
                    ->onIcon('heroicon-m-star')
                    ->offIcon('heroicon-m-star')
                    ->afterStateUpdated(fn (Category $record, bool $state) => $record->save()),
                TextColumn::make('position')->label('Позиция')->sortable(),
                TextColumn::make('slug')->label('Слаг')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Создана')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->headerActions([
                Action::make('createChild')
                    ->label('Создать дочернюю')
                    ->icon('heroicon-m-plus')
                    ->url(fn () => CategoryResource::getUrl('create', ['type' => 'child']))
                    ->color('primary'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ред.')
                    ->url(fn (Category $record) => CategoryResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}
