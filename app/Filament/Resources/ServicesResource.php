<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServicesResource\Pages;
use App\Filament\Resources\ServicesResource\RelationManagers;
use App\Models\Service;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\RawJs;

class ServicesResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Change name in URL
    protected static ?string $slug = 'services-other';

    // Change name in navigation
    protected static ?string $navigationLabel = 'Services';

    // Change name in page header
    protected static ?string $modelLabel = 'Services Other';

    // Change name in page header navigation
    protected static ?string $navigationGroup = 'Services Management'; 

    // Sort order in navigation
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->placeholder(__('Enter Name')),
                TextInput::make('price')
                ->required()
                ->prefix('VNĐ')
                ->step(100)
                ->mask(RawJs::make('$money($input)'))
                ->numeric(),
                Textarea::make('description')
                ->rows(10)
                ->cols(20)
                ->nullable()
                ->placeholder(__('Enter Description')),
                Radio::make('status')
                ->options([
                    '1' => 'Active',
                    '0' => 'Inactive',
                ])
                ->default('1'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                ->label('Price (VNĐ)')
                ->numeric(
                    decimalPlaces: 0,
                    decimalSeparator: '.',
                    thousandsSeparator: ',',
                ),
                IconColumn::make('status')
                ->boolean()
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateServices::route('/create'),
            'edit' => Pages\EditServices::route('/{record}/edit'),
        ];
    }
}
