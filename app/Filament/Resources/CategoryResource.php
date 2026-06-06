<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Categorías';

    protected static ?string $modelLabel = 'Categoría';

    protected static ?string $pluralModelLabel = 'Categorías';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(100)
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(100)
                ->unique(ignoreRecord: true),

            Textarea::make('description')
                ->label('Descripción')
                ->rows(3)
                ->columnSpanFull(),

            Select::make('icon')
                ->label('Ícono')
                ->searchable()
                ->allowHtml()
                ->native(false)
                ->options(collect([
                    'store','shopping','cart','cart-outline','shopping-outline','tag','tag-outline',
                    'package','package-variant','package-variant-closed','food','food-fork-drink',
                    'food-variant','food-apple','coffee','coffee-outline','pizza','cake',
                    'cake-variant','silverware-fork-knife','silverware','hamburger','cupcake',
                    'ice-cream','noodles','bread-slice','egg-fried','hospital','hospital-box',
                    'medical-bag','pill','pill-multiple','stethoscope','heart','heart-pulse',
                    'heart-outline','needle','tooth','tooth-outline','eye','brain','bandage',
                    'ambulance','pharmacy','school','book','book-open','book-outline','pencil',
                    'pencil-box','desk','pen','notebook','graduation-cap','library','teach',
                    'car','car-outline','bus','truck','taxi','motorcycle','bicycle','airplane',
                    'train','ferry','fuel','gas-station','steering','car-wrench','home',
                    'home-outline','hotel','bed','bed-outline','sofa','couch','apartment',
                    'office-building','office-building-outline','city','domain','briefcase',
                    'briefcase-outline','account-tie','handshake','scale-balance','gavel','cash',
                    'currency-usd','bank','bank-outline','calculator','chart-line','file-document',
                    'printer','wrench','hammer','tools','screwdriver','cog','cog-outline','pipe',
                    'broom','washing-machine','fridge','television','scissors','lipstick',
                    'hair-dryer','spa','nail-polish','face-woman','mirror','lotion-plus','music',
                    'music-note','movie','gamepad','gamepad-variant','theater','soccer',
                    'basketball','tennis','swimming','dumbbell','yoga','ticket','laptop',
                    'computer','phone','cellphone','tablet','headphones','television-play','wifi',
                    'bluetooth','chip','monitor','flower','leaf','tree','nature','paw','cat',
                    'dog','horse','fish','barn','image','camera','brush','palette','art','draw',
                    'account','account-group','people','human','human-male-female','baby',
                    'baby-carriage','recycle','earth','water','fire','snowflake','umbrella',
                    'weather-sunny','weather-night','map-marker','map','compass','navigation',
                    'flag','star','star-outline','crown','shield','key','lock','unlock',
                ])->mapWithKeys(fn ($icon) => [
                    'mdi-' . $icon => '<i class="mdi mdi-' . $icon . '" style="margin-right:6px;font-size:1.1rem;vertical-align:middle;"></i>' . $icon,
                ])->toArray()),

            ColorPicker::make('color')
                ->label('Color'),

            TextInput::make('order')
                ->label('Orden')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Activa')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                ColorColumn::make('color')
                    ->label('Color'),

                TextColumn::make('icon')
                    ->label('Ícono')
                    ->html()
                    ->formatStateUsing(fn ($state) => $state
                        ? '<i class="mdi ' . e($state) . '" style="font-size:1.4rem;"></i>'
                        : '—'),

                TextColumn::make('businesses_count')
                    ->label('Negocios')
                    ->counts('businesses')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Activa'),

                TextColumn::make('order')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->actions([EditAction::make()])
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
