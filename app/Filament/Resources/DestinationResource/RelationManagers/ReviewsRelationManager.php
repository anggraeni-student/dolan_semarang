<?php

namespace App\Filament\Resources\DestinationResource\RelationManagers;

use App\Models\Review;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('user_id')
                    ->label('Penulis')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('score_view')->label('Pemandangan')->numeric()->min(1)->max(5)->default(5)->required(),
                TextInput::make('score_access')->label('Akses')->numeric()->min(1)->max(5)->default(5)->required(),
                TextInput::make('score_cleanliness')->label('Kebersihan')->numeric()->min(1)->max(5)->default(5)->required(),
                TextInput::make('score_facility')->label('Fasilitas')->numeric()->min(1)->max(5)->default(5)->required(),
                Textarea::make('body')->label('Isi Review')->rows(3)->required(),
                DatePicker::make('visited_at')->label('Tanggal Kunjungan'),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                TextColumn::make('user.name')->label('Penulis')->searchable(),
                TextColumn::make('score')->label('Skor')
                    ->badge()
                    ->color(fn ($state) => $state >= 4.5 ? 'success' : ($state >= 3.5 ? 'warning' : 'danger')),
                TextColumn::make('body')->label('Review')->limit(80)->wrap(),
                TextColumn::make('helpful_count')->label('Membantu')->sortable(),
                TextColumn::make('visited_at')->label('Dikunjungi')->date('d M Y'),
            ])
            ->filters([])
            ->headerActions([
                Actions\CreateAction::make()->label('Tambah Review'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }
}
