<?php

namespace App\Filament\Resources;

use App\Enums\HouseRoomStatus;
use App\Enums\UserRole;
use App\Filament\Resources\HouseResource\Pages;
use App\Filament\Resources\HouseResource\RelationManagers\RoomsRelationManager;
use App\Models\District;
use App\Models\House;
use App\Models\Province;
use App\Models\User;
use App\Models\Ward;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HouseResource extends Resource
{
    protected static ?string $model = House::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Change name in page header navigation
    protected static ?string $navigationGroup = 'House and Room Management';

    // Sort order in navigation
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name house')
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Name'))
                    ->translateLabel(),
                Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->required()
                    ->placeholder(__('Select Owner'))
                    ->options(function () {
                        return User::where('role', UserRole::Owner)->pluck('name', 'id');
                    })
                    ->translateLabel(),
                RichEditor::make('description')
                    ->maxLength(65535)
                    ->columnSpan('full')
                    ->placeholder(__('Description'))
                    ->translateLabel(),
                Select::make('province_id')
                    ->required()
                    ->label('Province')
                    ->options(Province::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function (Set $set) {
                        $set('district_id', null);
                        $set('ward_id', null);
                    })
                    ->live(debounce: 1)
                    ->placeholder(__('Province'))
                    ->translateLabel(),
                Select::make('district_id')
                    ->required()
                    ->label('District')
                    ->options(function (Get $get) {
                        $provinces = Province::find($get('province_id'));

                        if (!$provinces) {
                            return District::all()->pluck('name', 'id');
                        }
                        return $provinces->districts->pluck('name', 'id');
                    })
                    ->searchable(['name', 'code_name'])
                    ->preload()
                    ->afterStateUpdated(fn(callable $set) => $set('ward_id', null))
                    ->live(debounce: 1)
                    ->placeholder(__('District'))
                    ->translateLabel(),
                Select::make('ward_id')
                    ->required()
                    ->label('Ward')
                    ->options(function (callable $get) {
                        $districts = District::find($get('district_id'));

                        if (!$districts) {
                            return Ward::all()->pluck('name', 'id');
                        }
                        return $districts->wards->pluck('name', 'id');
                    })
                    ->searchable(['name', 'code_name'])
                    ->preload()
                    ->placeholder(__('Ward'))
                    ->translateLabel(),
                TextInput::make('address')
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Address'))
                    ->translateLabel(),
                Radio::make('status')
                    ->options(HouseRoomStatus::class)
                    ->inline()
                    ->columnSpan('full')
                    ->default(HouseRoomStatus::Inactive)
                    ->required()
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->rowIndex()
                    ->translateLabel(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('province.name')
                    ->translateLabel(),
                TextColumn::make('status')
                    ->badge()
                    ->translateLabel(),
                TextColumn::make('owner.name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('rooms_count')
                    ->label('Count rooms')
                    ->counts('rooms'),
                // Tables\Columns\TextColumn::make('district.name')
                //     ->translateLabel(),
                // Tables\Columns\TextColumn::make('ward.name')
                //     ->translateLabel(),
                // Tables\Columns\TextColumn::make('address')
                //     ->sortable()
                //     ->translateLabel(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime('Y-m-d H:i:s', '+7')
                //     ->sortable()
                //     ->translateLabel(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime('Y-m-d H:i:s', '+7')
                //     ->sortable()
                //     ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null);
    }

public static function getEloquentQuery(): Builder
{
    $user = auth()->user();

    if ($user->role === UserRole::Admin) {
        return House::query();
    }

    if ($user->role === UserRole::Owner) {
        return House::query()->where('owner_id', $user->id);
    }

    return House::query();
}

    public static function getRelations(): array
    {
        return [
            RoomsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHouses::route('/'),
            'create' => Pages\CreateHouse::route('/create'),
            'edit'   => Pages\EditHouse::route('/{record}/edit'),
        ];
    }
}
