<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{ TextInput, Textarea, Toggle, Select };
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Название')->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', str($state)->slug())),
            TextInput::make('slug')->label('Слаг')->required()->unique(ignoreRecord: true),

            Select::make('parent_id')->label('Родитель')
                ->placeholder('— Корневая —')
                ->searchable()->preload()
                ->options(fn () => Category::query()
                    ->whereNull('parent_id')
                    ->pluck('name','id')
                )->visible(function (Get $get, ?Category $record) {
                    $type = request()->string('type')->toString();
                    if ($type === 'root') return false;
                    if ($type === 'child') return true;

                    return !$record?->parent_id;
                })
            ,

            Toggle::make('is_active')->label('Активна')->default(true)->columnSpan(2),
            Toggle::make('is_popular')->label('Популярная')->columnSpan(2)->default(false),
            Textarea::make('description')->label('Описание')->rows(6),

            SpatieMediaLibraryFileUpload::make('cover')
                ->label('Обложка')
                ->collection('cover')
                ->image()
                ->imageEditor()
                ->conversion('card')
                ->responsiveImages()
                ->downloadable(false)
                ->columnSpan(1),
            TextInput::make('position')->label('Позиция')->numeric()->default(0),

            TextInput::make('meta_title')->label('Meta title'),
            Textarea::make('meta_description')->label('Meta description')->rows(3),
        ])->columns(2);
    }
}
