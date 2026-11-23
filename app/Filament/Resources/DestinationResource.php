<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DestinationResource\Pages;
use App\Filament\Resources\DestinationResource\RelationManagers\ReviewsRelationManager;
use App\Models\Destination;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Destinasi';

    protected static ?string $modelLabel = 'destinasi';

    protected static \UnitEnum|string|null $navigationGroup = 'Konten';

    public static function getNavigationBadge(): ?string
    {
        return (string) Destination::count();
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Utama')->columns(2)->schema([
                    TextInput::make('name')
                        ->label('Nama Destinasi')
                        ->required()
                        ->maxLength(150)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                    TextInput::make('slug')
                        ->label('Slug URL')
                        ->required()
                        ->maxLength(170)
                        ->unique(ignoreRecord: true)
                        ->helperText('Auto dari nama, bisa diedit.'),

                    Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('location')
                        ->label('Lokasi')
                        ->required()
                        ->maxLength(150)
                        ->helperText('Contoh: Papua Barat Daya'),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),

                    TextInput::make('hero_image')
                        ->label('Path Gambar Hero')
                        ->required()
                        ->helperText('Relatif ke public/, contoh: images/raja-ampat.jpg'),
                ]),

                Section::make('Detail Kunjungan')->columns(2)->schema([
                    TextInput::make('lat')->label('Latitude')->numeric()->step(0.0000001),
                    TextInput::make('lng')->label('Longitude')->numeric()->step(0.0000001),
                    TextInput::make('htm')->label('HTM (Rp)')->numeric()->default(0)->prefix('Rp'),
                    TextInput::make('opening_hours')->label('Jam Buka')->placeholder('07.00–17.00'),
                    TextInput::make('best_time')->label('Waktu Terbaik')->placeholder('Okt–Apr'),
                    TextInput::make('access_info')->label('Info Akses'),
                    Toggle::make('is_featured')->label('Tampilkan di Beranda')->default(false)->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('hero_image')
                    ->label('Foto')
                    ->disk('public')
                    ->height(44)
                    ->width(66)
                    ->defaultImageUrl(fn ($record) => asset($record->hero_image)),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->location),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('reviews_avg_score')
                    ->label('Rating')
                    ->state(fn ($record) => $record->rating)
                    ->badge()
                    ->color(fn ($state) => $state >= 4.5 ? 'success' : ($state >= 3.5 ? 'warning' : 'danger')),

                TextColumn::make('review_count')
                    ->label('Review')
                    ->state(fn ($record) => $record->review_count)
                    ->sortable(),

                TextColumn::make('htm')
                    ->label('HTM')
                    ->money('IDR')
                    ->sortable(),

                ToggleColumn::make('is_featured')
                    ->label('Unggulan')
                    ->disabled(),

                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Unggulan'),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestination::route('/create'),
            'view' => Pages\ViewDestination::route('/{record}'),
            'edit' => Pages\EditDestination::route('/{record}/edit'),
        ];
    }
}
