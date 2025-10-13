<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Categories\Widgets\RootCategoriesTable;
use App\Filament\Resources\Categories\Widgets\ChildCategoriesTable;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            RootCategoriesTable::class,
            ChildCategoriesTable::class,
        ];
    }

    public function hasTable(): bool
    {
        return false;
    }
}
