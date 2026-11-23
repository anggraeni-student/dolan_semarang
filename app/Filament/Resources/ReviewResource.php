<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Review';

    protected static ?string $modelLabel = 'review';

    protected static \UnitEnum|string|null $navigationGroup = 'Konten';

    public static function getNavigationBadge(): ?string
    {
        return (string) Review::count();
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Select::make('user_id')->label('Penulis')
                    ->relationship('user', 'name')->searchable()->preload()->required(),
                Select::make('destination_id')->label('Destinasi')
                    ->relationship('destination', 'name')->searchable()->preload()->required(),
                TextInput::make('score_view')->label('Pemandangan')->numeric()->min(1)->max(5)->default(5)->required(),
                TextInput::make('score_access')->label('Akses')->numeric()->min(1)->max(5)->default(5)->required(),
                TextInput::make('score_cleanliness')->label('Kebersihan')->numeric()->min(1)->max(5)->default(5)->required(),
                TextInput::make('score_facility')->label('Fasilitas')->numeric()->min(1)->max(5)->default(5)->required(),
                Textarea::make('body')->label('Isi Review')->rows(4)->required(),
                DatePicker::make('visited_at')->label('Tanggal Kunjungan'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Penulis')->searchable()->sortable(),
                TextColumn::make('destination.name')->label('Destinasi')->searchable()->sortable(),
                TextColumn::make('score')->label('Skor')
                    ->badge()
                    ->color(fn ($state) => $state >= 4.5 ? 'success' : ($state >= 3.5 ? 'warning' : 'danger')),
                TextColumn::make('body')->label('Review')->limit(70)->wrap(),
                TextColumn::make('helpful_count')->label('Membantu')->sortable(),
                TextColumn::make('photo_count')->label('Foto')->sortable(),
                TextColumn::make('visited_at')->label('Dikunjungi')->date('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('destination')->label('Destinasi')
                    ->relationship('destination', 'name')->searchable()->preload(),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
