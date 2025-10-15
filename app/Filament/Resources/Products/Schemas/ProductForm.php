<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Rules\LeafCategory;
use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{
    Repeater, Hidden,
    TextInput, Textarea, Toggle, Select, RichEditor, SpatieMediaLibraryFileUpload
};


class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        // дефолтный набор "Основных"
        $defaultMainSpecs = [
            ['k' => 'Производитель',         'v' => ''],
            ['k' => 'Страна производитель',  'v' => ''],
            ['k' => 'Тип',                   'v' => ''],
            ['k' => 'Площадь помещения',     'v' => ''],
            ['k' => 'Производительность',    'v' => ''],
            ['k' => 'Уровень шума',          'v' => ''],
            ['k' => 'Питание',               'v' => ''],
            ['k' => 'Потребляемая мощность', 'v' => ''],
            ['k' => 'Энергоэффективность',   'v' => ''],
            ['k' => 'Габариты (Ш×В×Г)',      'v' => ''],
            ['k' => 'Вес',                   'v' => ''],
            ['k' => 'Гарантия',              'v' => ''],
        ];

        return $schema->components([
            Section::make('Контент')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')->label('Название')->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', str($state)->slug())),

                        TextInput::make('slug')->label('Слаг')->required()->unique(ignoreRecord: true),

                        Select::make('category_id')->label('Категория (лист)')
                            ->required()->searchable()->preload()
                            ->options(fn () => Category::query()
                                ->active()->defaultOrder()->leaves()->pluck('name','id'))
                            ->rules([new LeafCategory]),

                        TextInput::make('sku')->label('Артикул'),

                        TextInput::make('price')->label('Цена (₸)')
                            ->required()->numeric()->step(0.01)->minValue(0)
                            ->dehydrateStateUsing(fn ($state) => number_format((float) str_replace(',', '.', $state), 2, '.', '')),

                        Toggle::make('is_active')->label('Активен')->default(true),
                        Toggle::make('is_available')->label('В наличии')->default(true),
                    ]),

                    SpatieMediaLibraryFileUpload::make('cover')
                        ->label('Обложка')->collection('cover')
                        ->image()->imageEditor()->imageEditorAspectRatios(['4:3','1:1','16:9'])
                        ->conversion('card')->responsiveImages()->downloadable(false)
                        ->columnSpanFull(),

                    SpatieMediaLibraryFileUpload::make('gallery')
                        ->label('Галерея')->collection('gallery')
                        ->image()->multiple()->reorderable()->panelLayout('grid')
                        ->conversion('thumb')->responsiveImages()->downloadable(false)
                        ->columnSpanFull(),

                    RichEditor::make('description')->label('Описание')->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('meta_title')->label('Meta title'),
                        Textarea::make('meta_description')->label('Meta description')->rows(3),
                    ]),
                ])
                ->columnSpanFull(),

            Section::make('Характеристики')
                ->extraAttributes(['class' => 'rounded-xl border border-gray-200 dark:border-gray-700'])
                ->schema([

                    // скрытые флаги для управления свёрнутостью
                    Hidden::make('ui_collapse_main')->default(false),
                    Hidden::make('ui_collapse_extra')->default(false),

                    Grid::make(2)->schema([

                        // Левый столбец — Основные
                        Group::make()
                            ->schema([
                                Section::make('Основные')
                                    ->extraAttributes(['class' => 'rounded-lg border border-gray-200 dark:border-gray-700'])
                                    ->schema([
                                        Repeater::make('specifications.Основные')
                                            ->addActionLabel('Добавить параметр')
                                            ->itemLabel(fn (array $state) => $state['k'] ?? 'Параметр')
                                            ->default($defaultMainSpecs)
                                            ->afterStateHydrated(function ($component, $state) use ($defaultMainSpecs) {
                                                if (empty($state)) $component->state($defaultMainSpecs);
                                            })
                                            ->reorderable()
                                            ->collapsible()
                                            ->collapsed(fn (Get $get) => (bool) $get('ui_collapse_main'))
                                            ->schema([
                                                TextInput::make('k')->label('Параметр')->required(),
                                                TextInput::make('v')->label('Значение')->required(),
                                            ])->columns(2),
                                    ]),
                            ])
                            ->columnSpan(1),

                        Group::make()
                            ->schema([
                                Section::make('Дополнительные')
                                    ->extraAttributes(['class' => 'rounded-lg border border-gray-200 dark:border-gray-700'])
                                    ->schema([
                                        Repeater::make('specifications.Дополнительные')
                                            ->addActionLabel('Добавить параметр')
                                            ->itemLabel(fn (array $state) => $state['k'] ?? 'Параметр')
                                            ->reorderable()
                                            ->collapsible()
                                            ->collapsed(fn (Get $get) => (bool) $get('ui_collapse_extra'))
                                            ->schema([
                                                TextInput::make('k')->label('Параметр')->required(),
                                                TextInput::make('v')->label('Значение')->required(),
                                            ])->columns(2),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
                ])
                ->columnSpanFull(),

        ])->columns(1); // одна колонка секций, каждая — на всю ширину
    }
}
