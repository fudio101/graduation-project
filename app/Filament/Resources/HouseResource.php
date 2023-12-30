<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseResource\Pages;
use App\Models\District;
use App\Models\House;
use App\Models\Province;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HouseResource extends Resource
{
    protected static ?string $model = House::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Name'))
                    ->translateLabel(),
                Forms\Components\RichEditor::make('description')
                    ->maxLength(65535)
                    ->columnSpan('full')
                    ->placeholder(__('Description'))
                    ->translateLabel(),
                Forms\Components\Select::make('province_id')
                    ->options(Province::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function (Forms\Set $set) {
                        $set('district_id', null);
                        $set('ward_id', null);
                    })
                    ->live(debounce: 1)
                    ->placeholder(__('Province'))
                    ->translateLabel(),
                Forms\Components\Select::make('district_id')
                    ->options(function (Forms\Get $get) {
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
                Forms\Components\Select::make('ward_id')
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
                Forms\Components\TextInput::make('address')
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Address'))
                    ->translateLabel(),]);
    }

    public static function table(Table $table): Table
    {
        return $table
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHouses::route('/'),
            'create' => Pages\CreateHouse::route('/create'),
            'edit' => Pages\EditHouse::route('/{record}/edit'),
        ];
    }
}
