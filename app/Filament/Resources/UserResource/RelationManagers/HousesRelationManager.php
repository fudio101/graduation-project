<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\HouseRoomStatus;
use App\Enums\UserRole;
use App\Models\District;
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
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HousesRelationManager extends RelationManager
{
    protected static string $relationship = 'houses';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->rowIndex()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('province.name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('district.name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('ward.name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('address')
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s', '+7')
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i:s', '+7')
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

    public static function canViewForRecord(User|Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->role === UserRole::Owner;
    }
}
