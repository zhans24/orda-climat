<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Админ';
    protected static ?string $title = 'Профиль администратора';
    protected static ?string $slug = 'admin-profile';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.admin-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $u = auth()->user();

        $this->data = [
            'name'  => $u->name,
            'email' => $u->email,
            'password' => null,
            'password_confirmation' => null,
        ];
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                TextInput::make('name')
                    ->label('Имя')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('email')
                    ->label('Email')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('password')->visible(false),
                TextInput::make('password_confirmation')->visible(false),
            ])
            ->columns(2);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editAll')
                ->label('Изменить')
                ->icon('heroicon-o-pencil-square')
                ->modalHeading('Изменить профиль')
                ->modalSubmitActionLabel('Сохранить')
                ->color('primary')
                ->form([
                    TextInput::make('name')
                        ->label('Имя')
                        ->required()
                        ->default(fn () => auth()->user()->name),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->default(fn () => auth()->user()->email),

                    // Смена пароля (опционально):
                    TextInput::make('current_password')
                        ->label('Текущий пароль')
                        ->password()
                        ->revealable()
                        ->required(fn (callable $get) => filled($get('new_password')))
                        ->rule('current_password'),

                    TextInput::make('new_password')
                        ->label('Новый пароль')
                        ->password()
                        ->revealable()
                        // ->rules([PasswordRule::defaults(), 'confirmed'])
                        ->dehydrateStateUsing(fn ($state) => (string) $state),

                    TextInput::make('new_password_confirmation')
                        ->label('Подтверждение пароля')
                        ->password()
                        ->revealable()
                        ->requiredWith('new_password'),
                ])
                ->action(function (array $data): void {
                    $user = auth()->user();

                    // Обновляем имя / email
                    $user->name  = $data['name'] ?? $user->name;
                    $user->email = $data['email'] ?? $user->email;

                    $passwordWasChanged = false;

                    if (filled($data['new_password'] ?? null)) {
                        // current_password уже провалиден правилом выше
                        $user->password = Hash::make($data['new_password']);
                        $user->remember_token = Str::random(60);
                        $passwordWasChanged = true;
                    }

                    $user->save();

                    if ($passwordWasChanged) {
                        event(new PasswordReset($user));

                        if (method_exists(Auth::guard(), 'logoutOtherDevices')) {
                            Auth::logoutOtherDevices($data['new_password']);
                        }
                    }

                    $this->mount();

                    Notification::make()
                        ->title('Профиль обновлён')
                        ->success()
                        ->send();
                }),
        ];
    }
}
