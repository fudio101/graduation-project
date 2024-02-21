<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistrictResource\Pages;
use App\Models\District;
use App\Models\Province;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DistrictResource extends Resource
{
    protected static ?string $model = District::class;

    protected static ?string $navigationGroup = 'Administrative Unit';

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 1;

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
                Select::make('province_id')
                    ->relationship('province', 'name')
                    ->required()
                    ->placeholder(__('Province'))
                    ->options(function () {
                        return Province::pluck('name', 'id');
                    })
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
                TextColumn::make('province.name')
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
            'index' => Pages\ManageDistricts::route('/'),
        ];
    }

//    public static function getNavigationBadge(): ?string
//    {
//        return static::getModel()::count();
//    }
}
