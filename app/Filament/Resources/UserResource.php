<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\HousesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\RoomsRelationManager;
use App\Models\User;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $operation = $form->getOperation();
        $user = auth()->user();
        $roles = [];

        switch ($user->role) {
            case UserRole::Admin:
                $roles = [
                    UserRole::Admin->value      => UserRole::Admin->getLabel(),
                    UserRole::Owner->value      => UserRole::Owner->getLabel(),
                    UserRole::Manager->value    => UserRole::Manager->getLabel(),
                    UserRole::NormalUser->value => UserRole::NormalUser->getLabel(),
                ];
                break;
            case UserRole::Owner:
                $roles = [
                    UserRole::Manager->value    => UserRole::Manager->getLabel(),
                    UserRole::NormalUser->value => UserRole::NormalUser->getLabel(),
                ];
                if ($operation === 'view') {
                    $roles[UserRole::Owner->value] = UserRole::Owner->getLabel();
                }
                break;
            case UserRole::Manager:
                $roles = [
                    UserRole::NormalUser->value => UserRole::NormalUser->getLabel(),
                ];
                if ($operation === 'view') {
                    $roles[UserRole::Manager->value] = UserRole::Manager->getLabel();
                }
                break;
        }

        return $form
            ->schema([
                FileUpload::make('avatar_url')
                    ->disk('public')
                    ->directory('user-avatars')
                    ->visibility('private')
                    ->image()
                    ->avatar()
                    ->storeFileNamesIn('avatar_name')
                    ->imageEditor(fn(string $operation): bool => $operation !== 'view')
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->imageEditorEmptyFillColor('#000000')
                    ->imageEditorViewportWidth('1920')
                    ->imageEditorViewportHeight('1080')
                    ->circleCropper()
                    ->maxSize(2048)
                    ->placeholder(__('Avatar'))
                    ->translateLabel()
                    ->helperText('Max. 2MB')
                    ->columnSpan('full'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder(__('Email'))
                    ->translateLabel()
                    ->disabled(fn(string $operation): bool => $operation !== 'create'),
                TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Name'))
                    ->translateLabel(),
                Select::make('role')
                    ->options($roles)
                    ->required()
                    ->translateLabel(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->maxLength(255)
                    ->minLength(8)
                    ->placeholder(__('Password'))
                    ->translateLabel()
                    ->helperText(fn(string $operation): string => $operation === 'update' ? 'Leave this blank if you don\'t want to change the password.' : '')
                    ->hidden(fn(string $operation): bool => $operation === 'view'),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->rowIndex()
                    ->translateLabel(),
                ImageColumn::make('avatar_url')
                    ->visibility('private')
                    ->circular()
                    ->translateLabel(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('role')
                    ->badge()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('houses_count')
                    ->label('Count houses')
                    ->counts('houses'),
                // TextColumn::make('email_verified_at')
                //     ->dateTime('Y-m-d H:i:s', '+7')
                //     ->sortable()
                //     ->translateLabel(),
                // TextColumn::make('created_at')
                //     ->dateTime('Y-m-d H:i:s', '+7')
                //     ->sortable()
                //     ->translateLabel(),
                // TextColumn::make('updated_at')
                //     ->dateTime('Y-m-d H:i:s', '+7')
                //     ->sortable()
                //     ->translateLabel(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options(UserRole::class)
                    ->multiple()
                    ->label(__('Role')),
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
        $query = User::query();

        if ($user->role === UserRole::Admin) {
            return $query;
        }


        if ($user->role === UserRole::Owner) {
            $query->where(function (Builder $query) use ($user) {
                $query->orWhere('id', $user->id)
                    ->orWhere('role', UserRole::NormalUser)
                    ->orWhere(function (Builder $query) use ($user) {
                        $query->where('role', UserRole::Manager)
                            ->where(function (Builder $query) use ($user) {
                                $query->orWhereDoesntHave('rooms')
                                    ->orWhereHas('rooms.house', function (Builder $query) use ($user) {
                                        $query->where('owner_id', $user->id);
                                    });
                            });
                    });
            });
        }

        if ($user->role === UserRole::Manager) {
            $query->where(function (Builder $query) use ($user) {
                $query->orWhere('id', $user->id)
                    ->orWhere('role', UserRole::NormalUser);
            });
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            HousesRelationManager::class,
            RoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }
}
