<?php

namespace App\Filament\Resources;

use App\Enums\ContractStatus;
use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\User;
use App\Enums\UserRole;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

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
            Radio::make('status')
                ->options([
                    1 => 'Active',
                    0 => 'Inactive',
                ])
                ->required()
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
                TextColumn::make('status')
                    ->badge()
                    ->translateLabel(),
                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('End Date')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                ->options([
                    1 => 'Active',
                    0 => 'Inactive',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf') 
                    ->label('PDF')
                    ->color('success')
                    ->url(fn (Contract $record) => route('pdf', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }
}
