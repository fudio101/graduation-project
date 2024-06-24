<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillResource\Pages;
use App\Filament\Resources\BillResource\RelationManagers;
use App\Models\Bill;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Bill Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('month')
                    ->label('Month'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rooms.name')
                    ->label('Room')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('month'),
                TextColumn::make('status')
                    ->badge()
                    ->translateLabel(),
                TextColumn::make('total_money')
                    ->label('Total Money (vnd)')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        TextInput::make('month')
                        ->type('month')
                        ->default(date('Y-m'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->where('month', $data['month']);
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Bill $record) => $record->delete()),
                Tables\Actions\Action::make('pdf') 
                    ->label('Thanh toán')
                    ->color('info')
                    ->action(fn (Bill $record) => 
                        $record->update(['status' => 1])
                    )
                    ->requiresConfirmation()
                    // ->url(fn (Bill $record) => route('billing_room', $record))
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
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }
}
