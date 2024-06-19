<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\ContractResource\Pages;
use App\Models\Contract;
use App\Models\ContractEnd;
use App\Models\Room;
use App\Models\User;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationGroup = 'Contract Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->required()
                    ->placeholder(__('Select Member'))
                    ->options(function () {
                        return User::pluck('name', 'id');
                    })
                    ->translateLabel(),
                Radio::make('status')
                    ->options(function (Get $get) {
                        $status = $get('status');
                        return $status == 1
                            ? [1 => 'Active']
                            : [
                                1 => 'Active',
                                0 => 'Inactive',
                            ];
                    })
                    ->rules([
                        fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            $id_contract = $get('id');
                            $data_room = Contract::where('id', $id_contract)->with('room')->first();
                            $data_room = $data_room->room;
                            $is_checked_room = $data_room->isStatusActive();
                            if ($is_checked_room) {
                                $fail('This room is not available');
                            } else {
                                Room::where('id', $data_room->id)->update(['status' => 1, 'checked' => 1]);
                            }
                        },
                    ])
                    ->required()
                    ->translateLabel(),
                Fieldset::make('Time Period')
                    ->schema([
                        DatePicker::make('start_date')
                            ->required()
                            ->placeholder(__('Start Date'))
                            ->translateLabel(),
                        DatePicker::make('end_date')
                            ->required()
                            ->placeholder(__('End Date'))
                            ->translateLabel(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->translateLabel(),
                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('End Date')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('end-contract')
                    ->label('END')
                    ->color('info')
                    ->action(function (array $data, Contract $record): void {
                        DB::beginTransaction();
                        try {
                            // Tìm phòng và cập nhật trạng thái phòng
                            $room = Room::find($record->room_id);
                            if (!$room) {
                                throw new Exception('Room not found');
                            }
                            $room->update(['status' => 0, 'checked' => 0]);

                            // Tìm hợp đồng và cập nhật trạng thái hợp đồng
                            $contract = Contract::find($record->id);
                            if (!$contract) {
                                throw new Exception('Contract not found');
                            }
                            $contract->update([
                                'status'     => 0,
                                'start_date' => null,
                                'end_date'   => null,
                                'member_id'  => null,
                            ]);

                            // Tạo bản ghi mới trong ContractEnd
                            $contract_end = new ContractEnd();
                            $contract_end->room_id = $record->room_id;
                            $contract_end->member_id = $record->member_id;
                            $contract_end->start_date = $record->start_date;
                            $contract_end->end_date = $record->end_date;
                            $contract_end->termination_date = now();
                            $contract_end->description = 'End Contract';
                            $contract_end->save();

                            DB::commit();
                        } catch (Exception $e) {
                            DB::rollBack();
                            // Xử lý ngoại lệ
                            Log::error('Failed to end contract: ' . $e->getMessage());
                            throw $e; // Hoặc bạn có thể tùy chỉnh thông báo lỗi gửi đến người dùng
                        }
                    })
                    ->visible(function (Contract $record): bool {
                        return $record->status->value === 1;
                    })
                    ->requiresConfirmation(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->color('success')
                    ->url(fn(Contract $record) => route('pdf', $record))
                    ->openUrlInNewTab()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $query = Contract::query();

        if ($user->role !== UserRole::Admin) {
            if ($user->role === UserRole::Owner) {
                $query->whereHas('room', function (Builder $query) use ($user) {
                    $query->whereHas('house', function (Builder $query) use ($user) {
                        $query->where('owner_id', $user->id);
                    });
                });
            }

            if ($user->role === UserRole::Manager) {
                $query->whereHas('room', function (Builder $query) use ($user) {
                    $query->where('manager_id', $user->id);
                });
            }
        }

        return $query;
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
            'index'  => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit'   => Pages\EditContract::route('/{record}/edit'),
        ];
    }
}
