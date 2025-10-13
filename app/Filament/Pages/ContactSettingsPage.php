<?php

namespace App\Filament\Pages;

use App\Models\ContactSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

class ContactSettingsPage extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Контакты';
    protected static ?string $title = 'Контакты сайта';
    protected static ?string $slug = 'contact-settings-page';
    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.contact-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $s = ContactSettings::singleton();

        $this->data = [
            'company_name' => $s->company_name,
            'tagline'      => $s->tagline,
            'phone'        => $s->phone,
            'whatsapp'     => $s->whatsapp,
            'instagram'    => $s->instagram,
            'tiktok'       => $s->tiktok,
            'address'      => $s->address,
            'map_iframe'   => $s->map_iframe,
        ];
    }

    public function schema(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            TextInput::make('company_name')->label('Название компании')->placeholder('OrdaKlimat'),
            TextInput::make('tagline')->label('Слоган')->placeholder('Ваш климат. Ваш комфорт'),

            TextInput::make('phone')->label('Телефон')->placeholder('+7 747 123 45 67'),
            TextInput::make('whatsapp')->label('WhatsApp')->placeholder('ссылка или номер'),
            TextInput::make('instagram')->label('Instagram')->placeholder('ссылка или логин @ordaclimatkz'),
            TextInput::make('tiktok')->label('TikTok')->placeholder('ссылка или логин @ordaclimatkz'),
            TextInput::make('email')->label('Почта')->email()->placeholder('ordaclimat@gmail.com'),

            TextInput::make('address')->label('Адрес')->placeholder('г. Астана, ул. Айнаколь 139, основной склад и офис'),
            Textarea::make('map_iframe')->label('Карта (iframe)')->rows(4),

        ])->columns(2);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Сохранить')
                ->color('primary')
                ->action(function () {
                    $payload = collect($this->data ?? [])
                        ->map(function ($v) {
                            if (is_string($v)) {
                                $v = trim($v);
                                return $v === '' ? null : $v;
                            }
                            return $v === '' ? null : $v;
                        })
                        ->all();

                    ContactSettings::singleton()->update($payload);

                    $this->data = $payload;

                    Notification::make()->title('Сохранено')->success()->send();
                }),
        ];
    }
}
