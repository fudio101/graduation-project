<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WardResource\Pages;
use App\Filament\Resources\WardResource\RelationManagers;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WardResource extends Resource
{
    protected static ?string $model = Ward::class;

    protected static ?string $navigationGroup = 'Administrative Unit';

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->translateLabel(),
                Forms\Components\TextInput::make('name')
                    ->translateLabel(),
                Forms\Components\TextInput::make('division_type')
                    ->translateLabel(),
                Forms\Components\TextInput::make('code_name')
                    ->translateLabel(),
                Forms\Components\TextInput::make('district_id')
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('division_type')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('code_name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('district.name')
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
            'index' => Pages\ManageWards::route('/'),
        ];
    }

//    public static function getNavigationBadge(): ?string
//    {
//        return static::getModel()::count();
//    }
}
