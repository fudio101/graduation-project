<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ElectricRecordResource\Pages;
use App\Filament\Resources\ElectricRecordResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;

class ElectricRecordResource extends Resource
{
    // protected static ?string $model = ElectricRecord::class;
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Bill Management';

    protected static ?string $navigationLabel = 'Electric and Water Record';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable(),
                TextInputColumn::make('electric_record')
                ->rules(['required', 'integer'])
                ->label('Electric Record'),
                TextInputColumn::make('water_record'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListElectricRecords::route('/'),
            'create' => Pages\CreateElectricRecord::route('/create'),
            'edit' => Pages\EditElectricRecord::route('/{record}/edit'),
        ];
    }

}
