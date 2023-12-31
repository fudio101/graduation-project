<?php

namespace App\Filament\Resources;

use App\Enums\HouseRoomStatus;
use App\Filament\Resources\RoomsResource\Pages;
use App\Filament\Resources\RoomsResource\RelationManagers;
use App\Filament\Resources\RoomsResource\RelationManagers\ServicesRelationManager;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\House;
use App\Models\Service;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
class RoomsResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Change name in page header navigation
    protected static ?string $navigationGroup = 'House and Room Management';

    // Sort order in navigation
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
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
                Select::make('room_type_id')
                    ->relationship('roomType', 'name')
                    ->required()
                    ->placeholder(__('Select Room Type'))
                    ->options(function () {
                        return RoomType::pluck('name', 'id');
                    })
                    ->translateLabel(),
                Select::make('house_id')
                    ->relationship('house', 'name')
                    ->required()
                    ->placeholder(__('Select House'))
                    ->options(function () {
                        return House::pluck('name', 'id');
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
            //     Section::make('Services')->schema([
            //         Select::make('services')
            //             ->relationship('services', 'name')
            //             ->options(function () {
            //                 return Service::pluck('name', 'id');
            //             })
            //             ->multiple()
            //             ->translateLabel(),
            //         // TextInput::make('quantity')
            //         //     ->label('Quantity')
            //         //     ->type('number')
            //         //     ->translateLabel(),
            //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name room')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roomType.name')
                    ->label('Room Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('house.name')
                    ->label('House')
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
            ServicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRooms::route('/create'),
            'edit'   => Pages\EditRooms::route('/{record}/edit'),
        ];
    }
}
