<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{TextInput, Hidden};
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // флаг "пользователь редактировал slug"
            Hidden::make('slug_manually_edited')
                ->default(false)
                ->dehydrated(false),

            TextInput::make('name')
                ->label('Название')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, $set, $get) {
                    if (! $get('slug_manually_edited')) {
                        $set('slug', Str::slug((string) $state));
                    }
                }),

            TextInput::make('slug')
                ->label('Слаг')
                ->rule('alpha_dash')
                ->unique(table: 'categories', column: 'slug', ignoreRecord: true)
                ->helperText('Можно отредактировать вручную')
                ->afterStateUpdated(function ($state, $set) {
                    $set('slug_manually_edited', true);
                }),

            TextInput::make('position')->numeric()->default(0),
        ])->columns(2);
    }
}
