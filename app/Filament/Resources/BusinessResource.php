<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessResource\Pages;
use App\Models\Business;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Negocios';

    protected static ?string $modelLabel = 'Negocio';

    protected static ?string $pluralModelLabel = 'Negocios';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Tabs')->tabs([

                Tab::make('Básico')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Nombre del negocio')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Grid::make(1)->schema([
                            Toggle::make('is_active')->label('Activo')->default(true)->inline(false),
                            Toggle::make('is_featured')->label('Destacado')->default(false)->inline(false),
                        ]),
                    ]),

                    TextInput::make('short_description')
                        ->label('Descripción corta (para la tarjeta)')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('long_description')
                        ->label('Descripción completa')
                        ->rows(6)
                        ->columnSpanFull(),
                ]),

                Tab::make('Contacto')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->required()
                            ->placeholder('573001234567')
                            ->helperText('Número internacional sin + ni espacios'),

                        TextInput::make('phone')
                            ->label('Teléfono fijo')
                            ->placeholder('6075551234'),

                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email(),

                        TextInput::make('website')
                            ->label('Sitio web')
                            ->url()
                            ->placeholder('https://'),

                        TextInput::make('instagram')
                            ->label('Instagram')
                            ->placeholder('nombre_usuario'),

                        TextInput::make('facebook')
                            ->label('Facebook')
                            ->placeholder('nombre_pagina'),

                        TextInput::make('tiktok')
                            ->label('TikTok')
                            ->placeholder('nombre_usuario'),

                        TextInput::make('youtube')
                            ->label('YouTube')
                            ->placeholder('@canal'),
                    ]),
                ]),

                Tab::make('Ubicación')->schema([
                    TextInput::make('address')
                        ->label('Dirección')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Toggle::make('has_location')
                        ->label('Agregar ubicación')
                        ->default(false)
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if (!$state) {
                                $set('latitude', null);
                                $set('longitude', null);
                            }
                        })
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('latitude')
                            ->label('Latitud')
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('7.37560000')
                            ->autocomplete('off'),

                        TextInput::make('longitude')
                            ->label('Longitud')
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('-72.64930000')
                            ->autocomplete('off'),
                    ])->hidden(fn ($get) => !$get('has_location')),

                    View::make('filament.forms.map-picker')
                        ->columnSpanFull()
                        ->hidden(fn ($get) => !$get('has_location')),
                ]),

                Tab::make('Imágenes')->schema([
                    FileUpload::make('main_image')
                        ->label('Imagen principal')
                        ->image()
                        ->disk('public')
                        ->directory('businesses/main')
                        ->maxSize(15360)
                        ->columnSpanFull(),

                    FileUpload::make('gallery_images')
                        ->label('Galería de imágenes')
                        ->image()
                        ->multiple()
                        ->disk('public')
                        ->directory('businesses/gallery')
                        ->maxSize(15360)
                        ->reorderable()
                        ->columnSpanFull(),
                ]),

                Tab::make('Horario')->schema([
                    Repeater::make('schedule')
                        ->label('Horario de atención')
                        ->schema([
                            Select::make('day')
                                ->label('Día')
                                ->options([
                                    'Lunes' => 'Lunes',
                                    'Martes' => 'Martes',
                                    'Miércoles' => 'Miércoles',
                                    'Jueves' => 'Jueves',
                                    'Viernes' => 'Viernes',
                                    'Sábado' => 'Sábado',
                                    'Domingo' => 'Domingo',
                                ])
                                ->required(),

                            TextInput::make('open')
                                ->label('Apertura')
                                ->placeholder('08:00'),

                            TextInput::make('close')
                                ->label('Cierre')
                                ->placeholder('18:00'),

                            Toggle::make('closed')
                                ->label('Cerrado')
                                ->default(false),
                        ])
                        ->columns(4)
                        ->columnSpanFull()
                        ->collapsible()
                        ->defaultItems(0),
                ]),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('main_image')
                    ->label('Imagen')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('address')
                    ->label('Dirección')
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->toggleable(),

                ToggleColumn::make('is_active')
                    ->label('Activo'),

                ToggleColumn::make('is_featured')
                    ->label('Destacado'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name'),

                TernaryFilter::make('is_active')
                    ->label('Activo'),

                TernaryFilter::make('is_featured')
                    ->label('Destacado'),
            ])
            ->actions([ViewAction::make(), EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'view' => Pages\ViewBusiness::route('/{record}'),
            'edit' => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }
}
