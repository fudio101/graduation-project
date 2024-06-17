<?php

namespace App\Filament\Resources;

use App\Enums\WaterElectricStatus;
use App\Filament\Resources\ElectricManagerResource\Pages;
use App\Filament\Resources\ElectricManagerResource\RelationManagers;
use App\Models\ElectricManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Columns\TextColumn;

class ElectricManagerResource extends Resource
{
    protected static ?string $model = ElectricManager::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Electric Manager';

    protected static ?string $navigationGroup = 'Services Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Radio::make('status')
                ->options(WaterElectricStatus::class)
                ->translateLabel()
                ->required(),
            Tabs::make('status')
                ->tabs([
                Tabs\Tab::make('0')
                ->label('Quantity')
                    ->schema([
                        TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->rules(['required', 'min:0']),
                    ]),
                Tabs\Tab::make('1')
                ->label('Step')
                    ->schema([
                        KeyValue::make('step')
                            ->label('Step')
                            ->keyLabel('Number Step')
                            ->valueLabel('Price (vnđ)')
                            ->keyPlaceholder('Enter Price ex: 1000')
                            ->addable(false)
                            ->required()
                            ->rules(['min:0']),
                    ]),
            ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('house.name')
                ->label('House Name')
                ->searchable()
                ->sortable(),
            TextColumn::make('status')
            ->badge()
            ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListElectricManagers::route('/'),
            'create' => Pages\CreateElectricManager::route('/create'),
            'edit' => Pages\EditElectricManager::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
       return false;
    }
}
