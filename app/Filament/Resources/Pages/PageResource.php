<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\Pages\Tables\PagesTable;
use App\Models\Page;
use Filament\Resources\Resource;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|null|\BackedEnum $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static string|null|\UnitEnum $navigationGroup = 'Контент';
    protected static ?string $navigationLabel = 'Страницы';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return PageForm::configure($schema);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return PagesTable::configure($table);
    }


    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
