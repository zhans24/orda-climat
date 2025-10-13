<?php

namespace App\Filament\Resources\Leads\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->label('Имя')->searchable()->toggleable(),
                TextColumn::make('phone')->label('Телефон')->searchable()->toggleable(),
                TextColumn::make('message')->label('Сообщение')->limit(60)->wrap()->toggleable(),
                BadgeColumn::make('status')
                    ->label('Статус')
                    ->colors([
                        'warning' => 'new',
                        'info'    => 'in_progress',
                        'success' => 'done',
                        'danger'  => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state) => [
                        'new'         => 'Новая',
                        'in_progress' => 'В работе',
                        'done'        => 'Закрыта',
                        'rejected'    => 'Отклонена',
                    ][$state] ?? $state)
                    ->sortable(),
                TextColumn::make('created_at')->label('Создана')->dateTime('d.m.Y H:i')->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Статус')->options([
                    'new'         => 'Новая',
                    'in_progress' => 'В работе',
                    'done'        => 'Закрыта',
                    'rejected'    => 'Отклонена',
                ]),
            ])
            ->recordActions([
                // Быстрое изменение статуса
                Action::make('set_in_progress')->label('В работу')->color('info')
                    ->visible(fn ($record) => $record->status !== 'in_progress')
                    ->action(fn ($record) => $record->update(['status' => 'in_progress'])),

                Action::make('set_done')->label('Закрыть')->color('success')
                    ->visible(fn ($record) => $record->status !== 'done')
                    ->action(fn ($record) => $record->update(['status' => 'done'])),

                Action::make('set_rejected')->label('Отклонить')->color('danger')
                    ->visible(fn ($record) => $record->status !== 'rejected')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['status' => 'rejected'])),

                EditAction::make()->label('Ред.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}
