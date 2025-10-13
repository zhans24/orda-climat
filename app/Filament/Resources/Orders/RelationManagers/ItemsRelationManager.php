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
                        $set('code', $p->code);
                        $set('price', $p->price);
                        // можно сразу пересчитать сумму
                        $set('sum', (float)$p->price * (float)($this->data['quantity'] ?? 1));
                    }
                }),

            TextInput::make('name')
                ->label('Название')
                ->required()
                ->maxLength(255),

            TextInput::make('code')
                ->label('CODE')
                ->maxLength(255),

            TextInput::make('quantity')
                ->label('Кол-во')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $set('sum', (float)$state * (float)$get('price'));
                }),

            TextInput::make('price')
                ->label('Цена')
                ->numeric()
                ->prefix('₸')
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $set('sum', (float)$state * (float)$get('quantity'));
                }),

            TextInput::make('sum')
                ->label('Сумма')
                ->numeric()
                ->prefix('₸')
                ->readOnly()
                ->dehydrated(true)
                ->helperText('Пересчитывается как quantity × price'),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->label('Товар')->limit(40)->searchable(),
                TextColumn::make('code')->label('code')->toggleable(),
                TextColumn::make('quantity')->label('Кол-во'),
                TextColumn::make('price')->label('Цена')->money('KZT'),
                TextColumn::make('sum')->label('Сумма')->money('KZT'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->after(function () {
                        $this->getOwnerRecord()->recalcTotal();
                        $this->getOwnerRecord()->refresh();
                        $this->dispatch('$refresh');
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->after(function () {
                        $this->getOwnerRecord()->recalcTotal();
                        $this->getOwnerRecord()->refresh();
                        $this->dispatch('$refresh');
                    }),
                DeleteAction::make()
                    ->after(function () {
                        $this->getOwnerRecord()->recalcTotal();
                        $this->getOwnerRecord()->refresh();
                        $this->dispatch('$refresh');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
