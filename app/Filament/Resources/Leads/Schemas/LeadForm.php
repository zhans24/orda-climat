<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{ TextInput, Textarea, Select };

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Имя'),
            TextInput::make('phone')->label('Телефон')->required(),
            Textarea::make('message')->label('Сообщение')->rows(5)->columnSpanFull(),
            Select::make('status')->label('Статус')
                ->options([
                    'new'         => 'Новая',
                    'in_progress' => 'В работе',
                    'done'        => 'Закрыта',
                    'rejected'    => 'Отклонена',
                ])
                ->default('new')
                ->required(),
        ])->columns(2);
    }
}
