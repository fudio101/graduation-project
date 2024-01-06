<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseResource\Pages;
use App\Models\District;
use App\Models\House;
use App\Models\Province;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

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
                Select::make('manager_id')
                    ->relationship('manager', 'name')
                    ->required()
                    ->placeholder(__('Select Manager'))
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
                    ->afterStateUpdated(function (Forms\Set $set) {
                        $set('district_id', null);
                        $set('ward_id', null);
                    })
                    ->live(debounce: 1)
                    ->placeholder(__('Province'))
                    ->translateLabel(),
                Select::make('district_id')
                    ->label('District')
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
                Select::make('ward_id')
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
                TextColumn::make('index')
                    ->rowIndex()
                    ->translateLabel(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('province.name')
                    ->translateLabel(),
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
                TextColumn::make('manager.name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),                    
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

    public static function getRelations(): array
    {
        return [
                //
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
