<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'pengguna';

    protected static \UnitEnum|string|null $navigationGroup = 'Pengelolaan';

    public static function getNavigationBadge(): ?string
    {
        return (string) User::count();
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')->label('Nama')->required()->maxLength(120),
                TextInput::make('username')->label('Username')->required()->maxLength(80)
                    ->unique(ignoreRecord: true)->helperText('Untuk URL profil: /u/{username}'),
                TextInput::make('email')->label('Email')->email()->required()->maxLength(150)->unique(ignoreRecord: true),
                TextInput::make('password')->label('Password')
                    ->password()->revealable()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation) => $operation === 'create'),
                TextInput::make('avatar_initials')->label('Inisial Avatar')->maxLength(4)->default('JJ'),
                TextInput::make('provinces_visited')->label('Provinsi Dikunjungi')->numeric()->default(0)->min(0),
                TextInput::make('bio')->label('Bio')->maxLength(255),
                Toggle::make('is_admin')->label('Akses Panel Admin')->helperText('Izinkan login di /admin'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('avatar_initials')->label('Avatar')->badge()->color('primary'),
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('username')->label('Username')->searchable()
                    ->formatStateUsing(fn ($state) => '@'.$state),
                TextColumn::make('email')->label('Email')->searchable()->copyable(),
                TextColumn::make('reviews_count')->label('Review')->counts('reviews')->sortable(),
                TextColumn::make('provinces_visited')->label('Provinsi')->sortable(),
                TextColumn::make('is_admin')->label('Admin')->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Admin' : 'User')
                    ->color(fn ($state) => $state ? 'danger' : 'gray'),
                TextColumn::make('created_at')->label('Gabung')->date('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')->label('Admin'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
