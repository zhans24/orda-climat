<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('parent_id')->required()->label("Родительская категория")
                    ->numeric(),
                TextInput::make('name')
                    ->required()->label("Название"),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0)->label("Позиция"),
            ]);
    }
}
