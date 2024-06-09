<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractExpireResource\Pages;
use App\Filament\Resources\ContractExpireResource\RelationManagers;
use App\Models\ContractEnd;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;

class ContractExpireResource extends Resource
{
    protected static ?string $model = ContractEnd::class;

    protected static ?string $modelLabel = 'Contract Expire';

    protected static ?string $navigationGroup = 'Contract Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                ->relationship('member', 'name')
                ->required()
                ->placeholder(__('Select Member'))
                ->options(function () {
                    return User::pluck('name', 'id');
                })
                ->translateLabel(),
                DatePicker::make('termination_date')
                ->required()
                ->placeholder(__('Expiration Date'))
                ->translateLabel(),
                Fieldset::make('Time Period')
                ->schema([
                    DatePicker::make('start_date')
                        ->required()
                        ->placeholder(__('Start Date'))
                        ->translateLabel(),
                    DatePicker::make('end_date')
                        ->required()
                        ->placeholder(__('End Date'))
                        ->translateLabel(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('End Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('termination_date')
                    ->label('End Date')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListContractExpires::route('/'),
            'create' => Pages\CreateContractExpire::route('/create'),
            'edit' => Pages\EditContractExpire::route('/{record}/edit'),
        ];
    }
}
