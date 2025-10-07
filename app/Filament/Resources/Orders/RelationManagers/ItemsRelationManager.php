<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Позиции заказа';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Товар')
                ->options(Product::query()->pluck('name', 'id'))
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state) return;
                    $p = Product::find($state);
                    if ($p) {
                        $set('name', $p->name);
                        $set('sku', $p->sku);
                        $set('price', $p->price);
                    }
                }),

            TextInput::make('name')
                ->label('Название')
                ->required()
                ->maxLength(255),

            TextInput::make('sku')
                ->label('SKU')
                ->maxLength(255),

            TextInput::make('qty')
                ->label('Кол-во')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required(),

            TextInput::make('price')
                ->label('Цена')
                ->numeric()
                ->prefix('₸')
                ->required(),

            TextInput::make('sum')
                ->label('Сумма')
                ->numeric()
                ->prefix('₸')
                ->readOnly()
                ->helperText('Пересчитывается как qty × price при сохранении.'),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->label('Товар')->limit(40)->searchable(),
                TextColumn::make('sku')->label('SKU')->toggleable(),
                TextColumn::make('qty')->label('Кол-во'),
                TextColumn::make('price')->label('Цена')->money('KZT'),
                TextColumn::make('sum')->label('Сумма')->money('KZT'),
            ])
            ->filters([
                // при необходимости добавим фильтры по товару/цене/дате
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
