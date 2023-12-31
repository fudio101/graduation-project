<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\HousesRelationManager;
use App\Models\User;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
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
                    ->options(UserRole::class)
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
                    ->helperText('Let this blank if you don\'t want to change the password.')
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

    public static function getRelations(): array
    {
        return [
            HousesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
