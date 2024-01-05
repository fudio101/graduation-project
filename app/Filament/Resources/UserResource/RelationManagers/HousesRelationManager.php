<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HousesRelationManager extends RelationManager
{
    protected static string $relationship = 'houses';

    public function form(Form $form): Form
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
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('district.name')
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('ward.name')
                    ->sortable()
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
}
