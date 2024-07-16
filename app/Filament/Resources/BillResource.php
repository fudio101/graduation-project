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
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Support\RawJs;
use Filament\Forms\Components\Select;
use App\Enums\WaterElectricStatus;
use Filament\Forms\Components\Repeater;

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
                Fieldset::make('Time Period')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Start Date'),
                        DatePicker::make('end_date')
                            ->label('End Date'),
                    ]),
                Select::make('room')
                    ->relationship('rooms', 'name')
                    ->label('Tên phòng'),
                Select::make('room')
                    ->relationship('rooms.roomType', 'rental_price')
                    ->label('Tiền phòng'),
                Fieldset::make('Điện')
                    ->relationship('electricBill')
                    ->schema([
                        Select::make('type')
                            ->options(WaterElectricStatus::class)
                            ->label('Loại'),
                        TextInput::make('number')
                            ->label('Số điện'),
                        TextInput::make('costs')
                            ->label('Giá (VNĐ)')
                            ->mask(RawJs::make('$money($input)'))
                            ->numeric(),
                    ])
                    ->columns(3),
                Fieldset::make('Nước')
                    ->relationship('waterBill')
                    ->schema([
                        Select::make('type')
                            ->options(WaterElectricStatus::class)
                            ->label('Loại'),
                        TextInput::make('number')
                            ->label('Số nước'),
                        TextInput::make('costs')
                            ->label('Giá (VNĐ)')
                            ->mask(RawJs::make('$money($input)'))
                            ->numeric(),
                    ])
                    ->columns(3),
                Repeater::make('serviceBill')
                    ->relationship('serviceBill')
                    ->schema([
                        Select::make('service_id')
                            ->relationship('service', 'name')
                            ->label('Service')
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric(),     
                        TextInput::make('price')
                            ->label('Price (vnd)')
                            ->mask(RawJs::make('$money($input)'))
                            ->numeric(),
                    ])
                    ->columns(3),
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
                    ->visible(function (Bill $record): bool {
                        return $record->status->value === 0;
                    })
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
            // 'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }
}
