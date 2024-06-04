<?php

namespace App\Filament\Resources\HouseResource\RelationManagers;

use App\Enums\HouseRoomStatus;
use App\Enums\UserRole;
use App\Models\RoomType;
use App\Models\User;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name room')
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Name'))
                    ->translateLabel(),
                Select::make('manager_id')
                    ->relationship('manager', 'name')
                    ->required()
                    ->placeholder(__('Select Manager'))
                    ->options(function () {
                        return User::where('role', UserRole::Manager)->pluck('name', 'id');
                    })
                    ->translateLabel(),
                Select::make('room_type_id')
                    ->relationship('roomType', 'name')
                    ->required()
                    ->placeholder(__('Select Room Type'))
                    ->options(function () {
                        return RoomType::pluck('name', 'id');
                    })
                    ->translateLabel(),
                RichEditor::make('description')
                    ->maxLength(65535)
                    ->columnSpan('full')
                    ->placeholder(__('Description'))
                    ->translateLabel(),
                Radio::make('status')
                    ->options(HouseRoomStatus::class)
                    ->inline()
                    ->default(HouseRoomStatus::Inactive)
                    ->required()
                    ->translateLabel(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Name room')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roomType.name')
                    ->label('Room Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->translateLabel(),
                TextColumn::make('owner.name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
