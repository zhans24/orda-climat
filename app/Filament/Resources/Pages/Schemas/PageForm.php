<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\{Section, Grid};
use Filament\Forms\Components\{
    Repeater, TextInput, Textarea, Toggle, RichEditor, SpatieMediaLibraryFileUpload, Select
};

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основные данные')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('title')
                            ->label('Заголовок')
                            ->required(),
                        TextInput::make('slug')
                            ->label('Слаг')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),
                    Grid::make(3)->schema([
                        TextInput::make('meta_title')->label('Meta Title')->maxLength(70),
                        Textarea::make('meta_description')->label('Meta Description')->rows(2)->maxLength(160),
                        TextInput::make('template')->label('Шаблон')->maxLength(70)->required(),

                    ]),
                    Toggle::make('is_published')->label('Опубликовано')->default(true),
                ])
                ->columnSpanFull(),

            // ===================== О КОМПАНИИ (about) =====================
            Section::make('О компании')
                ->schema([
                    RichEditor::make('content.about_text')
                        ->label('Текст о компании')
                        ->toolbarButtons([
                            'bold','italic','underline','strike',
                            'h2','h3','blockquote','orderedList','bulletList',
                            'link','undo','redo',
                        ])
                        ->columnSpanFull(),

                    SpatieMediaLibraryFileUpload::make('about_image')
                        ->label('Изображение справа')
                        ->collection('about_image')
                        ->image()
                        ->maxFiles(1)
                        ->openable(),

                    SpatieMediaLibraryFileUpload::make('about_clients')
                        ->label('Логотипы клиентов')
                        ->collection('about_clients')
                        ->multiple()
                        ->image()
                        ->reorderable()
                        ->openable(),

                    SpatieMediaLibraryFileUpload::make('about_certificates')
                        ->label('Документы / Сертификаты')
                        ->collection('about_certificates')
                        ->multiple()
                        ->image()
                        ->reorderable()
                        ->openable(),
                ])
                ->columnSpanFull()
                ->visible(fn ($get) => $get('template') === 'about'),

            // ===================== ГЛАВНАЯ (home) =====================
            Section::make('Главная страница')
                ->schema([
                    // ---- INSTA ----
                    Grid::make(3)->schema([
                        TextInput::make('content.insta_title')
                            ->label('Insta: Заголовок')
                            ->maxLength(120),
                        TextInput::make('content.insta_subtitle')
                            ->label('Insta: Подзаголовок')
                            ->maxLength(160),
                        TextInput::make('content.insta_link')
                            ->label('Insta: Ссылка кнопки')
                            ->url(),
                    ]),
                    SpatieMediaLibraryFileUpload::make('insta_images')
                        ->label('Insta: Изображения (до 4 шт.)')
                        ->collection('insta_images')
                        ->multiple()
                        ->image()
                        ->maxFiles(4)
                        ->reorderable()
                        ->openable(),

                    // ---- CUSTOM / Услуги ----
                    Repeater::make('content.services_slides')
                        ->label('Услуги: текстовые слайды')
                        ->reorderable()
                        ->collapsible()
                        ->defaultItems(1)
                        ->schema([
                            TextInput::make('title')
                                ->label('Заголовок слайда')
                                ->maxLength(120)
                                ->required(),

                            Repeater::make('links')
                                ->label('Ссылки')
                                ->reorderable()
                                ->schema([
                                    TextInput::make('label')->label('Текст')->maxLength(60)->required(),
                                    TextInput::make('url')->label('URL')->url()->required(),
                                ])
                                ->defaultItems(2),

                            Repeater::make('bullets')
                                ->label('Пункты списка')
                                ->reorderable()
                                ->schema([
                                    TextInput::make('text')->label('Пункт')->maxLength(120)->required(),
                                ])
                                ->defaultItems(3),
                        ])
                        ->columns(1),

                    SpatieMediaLibraryFileUpload::make('services_images')
                        ->label('Услуги: Изображения (порядок = порядок слайдов)')
                        ->collection('services_images')
                        ->multiple()
                        ->image()
                        ->reorderable()
                        ->openable(),
                ])
                ->columnSpanFull()
                ->visible(fn ($get) => $get('template') === 'home'),


            // ===================== УСТАНОВКА (install-ac / install-vent) =====================
            Section::make('Страница установки (тексты и таблица)')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('content.block_title')
                            ->label('Заголовок блока над таблицей')
                            ->placeholder('Установка кондиционеров / Установка вентиляции'),

                        // если хочешь управлять заголовком колонки "Модель ..."
                        TextInput::make('content.model_col_title')
                            ->label('Заголовок колонки для модели')
                            ->placeholder('Модель кондиционера / Модель вентиляции'),
                    ]),

                    RichEditor::make('content.intro_text')
                        ->label('Вступительный текст (верх)')
                        ->toolbarButtons([
                            'bold','italic','underline','strike',
                            'h2','h3','blockquote','orderedList','bulletList',
                            'link','undo','redo',
                        ])
                        ->columnSpanFull(),

                    RichEditor::make('content.block_text')
                        ->label('Текст перед таблицей')
                        ->toolbarButtons([
                            'bold','italic','underline','strike',
                            'h2','h3','blockquote','orderedList','bulletList',
                            'link','undo','redo',
                        ])
                        ->columnSpanFull(),

                    Repeater::make('content.price_rows')
                        ->label('Строки таблицы')
                        ->helperText('Каждая запись = одна строка таблицы. Можно переносы строк внутри ячеек.')
                        ->reorderable()
                        ->collapsible()
                        ->defaultItems(3)
                        ->schema([
                            Textarea::make('service')
                                ->label('Услуга')
                                ->rows(3)
                                ->required(),

                            Textarea::make('model')
                                ->label('Модель (или характеристики)')
                                ->rows(3)
                                ->required(),

                            Textarea::make('price')
                                ->label('Цена')
                                ->rows(3)
                                ->required(),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),

                    RichEditor::make('content.rules_text')
                        ->label('Нижний пояснительный блок (правила, примечания)')
                        ->toolbarButtons([
                            'bold','italic','underline','strike',
                            'h2','h3','blockquote','orderedList','bulletList',
                            'link','undo','redo',
                        ])
                        ->columnSpanFull(),
                ])
                ->columnSpanFull()
                ->visible(fn ($get) => in_array($get('template'), ['install-ac', 'install-vent'])),


// ===================== Доставка/Оплата (delivery) =====================
            Section::make('Доставка и оплата')
                ->schema([
                    // Левая колонка (тексты по макету)
                    RichEditor::make('content.delivery_intro')
                        ->label('Вводный текст (верх)')
                        ->toolbarButtons(['bold','italic','underline','strike','h2','h3','blockquote','orderedList','bulletList','link','undo','redo'])
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('content.delivery_pay_title')
                            ->label('Заголовок блока 1')
                            ->default('Способы оплаты'),
                        RichEditor::make('content.delivery_pay_text')
                            ->label('Текст блока 1 (“Способы оплаты”)')
                            ->toolbarButtons(['bold','italic','underline','strike','orderedList','bulletList','link','undo','redo']),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('content.delivery_return_title')
                            ->label('Заголовок блока 2')
                            ->default('Возврат и отмена'),
                        RichEditor::make('content.delivery_return_text')
                            ->label('Текст блока 2 (“Возврат и отмена”)')
                            ->toolbarButtons(['bold','italic','underline','strike','orderedList','bulletList','link','undo','redo']),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('content.delivery_secure_title')
                            ->label('Заголовок блока 3')
                            ->default('Безопасность'),
                        RichEditor::make('content.delivery_secure_text')
                            ->label('Текст блока 3 (“Безопасность”)')
                            ->toolbarButtons(['bold','italic','underline','strike','orderedList','bulletList','link','undo','redo']),
                    ]),

                    // Правая колонка — картинка
                    SpatieMediaLibraryFileUpload::make('delivery_image')
                        ->label('Изображение справа')
                        ->collection('delivery_image')
                        ->image()
                        ->maxFiles(1)
                        ->openable(),
                ])
                ->columnSpanFull()
                ->visible(fn ($get) => $get('template') === 'delivery'),


            // ===================== ПОЛИТИКА КОНФИДЕНЦИАЛЬНОСТИ (privacy) =====================

            Section::make('Политика конфиденциальности')
                ->schema([
                    RichEditor::make('content.privacy_text')
                        ->label('Текст политики')
                        ->toolbarButtons([
                            'bold','italic','underline','strike',
                            'h2','h3','blockquote','orderedList','bulletList',
                            'link','undo','redo',
                        ])
                        ->columnSpanFull()
                        ->required(),
                ])
                ->columnSpanFull()
                ->visible(fn ($get) => $get('template') === 'privacy'),

        ]);
    }
}
