<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $modelLabel = 'kategori';

    protected static \UnitEnum|string|null $navigationGroup = 'Konten';

    public static function getNavigationBadge(): ?string
    {
        return (string) Category::count();
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')->label('Nama Kategori')->required()->maxLength(100)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                TextInput::make('slug')->label('Slug URL')->required()->maxLength(120)->unique(ignoreRecord: true),
                TextInput::make('icon')->label('Ikon Material')
                    ->helperText('Nama Material Icon, contoh: landscape, beach_access')
                    ->default('landscape'),
                TextInput::make('position')->label('Urutan Nav')->numeric()->default(0),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('icon')->label('Ikon')->badge()->color('gray'),
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('slug')->label('Slug')->searchable(),
                TextColumn::make('destinations_count')->label('Destinasi')->counts('destinations')->sortable(),
                TextColumn::make('position')->label('Urutan')->sortable(),
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
            ->defaultSort('position');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
