<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProvinceResource\Pages;
use App\Models\Province;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;

    protected static ?string $navigationGroup = 'Administrative Unit';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')
                    ->translateLabel(),
                TextInput::make('name')
                    ->translateLabel(),
                TextInput::make('division_type')
                    ->translateLabel(),
                TextInput::make('code_name')
                    ->translateLabel(),
                TextInput::make('phone_code')
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('division_type')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('code_name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('phone_code')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProvinces::route('/'),
        ];
    }

//    public static function getNavigationBadge(): ?string
//    {
//        return static::getModel()::count();
//    }
}
