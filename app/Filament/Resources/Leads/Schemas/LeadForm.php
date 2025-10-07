<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Имя'),
            TextInput::make('phone')->label('Телефон'),
            TextInput::make('email')->email(),
            Textarea::make('message')->label('Сообщение')->rows(4)->columnSpanFull(),
            TextInput::make('source')->label('Источник')->placeholder('landing / footer / whatsapp'),
            Select::make('status')
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
