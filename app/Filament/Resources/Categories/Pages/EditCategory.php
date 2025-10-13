<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function handleRecordUpdate($record, array $data): Category
    {
        $parentId = $data['parent_id'] ?? null;

        // защита: нельзя сделать родителем саму себя
        if ($parentId && (int)$parentId === (int)$record->id) {
            $parentId = null;
            $data['parent_id'] = null;
        }

        $record->fill($data);

        $parentChanged = $record->isDirty('parent_id');

        if ($parentChanged) {
            if ($parentId) {
                $parent = Category::findOrFail($parentId);
                $record->appendToNode($parent)->save();
            } else {
                $record->saveAsRoot();
            }
        } else {
            $record->save();
        }

        return $record;
    }
}
