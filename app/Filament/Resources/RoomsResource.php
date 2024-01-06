<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomsResource\Pages;
use App\Filament\Resources\RoomsResource\RelationManagers;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\House;
use BladeUI\Icons\Components\Icon;
use Filament\Forms;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

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
                    ->options([
                        0 => __('Inactive'),
                        1 => __('Active'),
                        2 => __('Pending'),
                        3 => __('Registered'),
                    ])
                    ->inline()
                    ->default(0)
                    ->required()
                    ->translateLabel(),
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
                IconColumn::make('status')
                ->icon(fn (int $state): string => match ($state) {
                    0 => 'heroicon-s-exclamation-circle',
                    1 => 'heroicon-s-check-circle',
                    2 => 'heroicon-s-wrench-screwdriver',
                    3 => 'heroicon-s-pencil',
                })
                ->color(fn (int $state): string => match ($state) {
                    0 => 'danger',
                    1 => 'success',
                    2 => 'gray',
                    3 => 'info',
                }),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRooms::route('/create'),
            'edit' => Pages\EditRooms::route('/{record}/edit'),
        ];
    }
}
