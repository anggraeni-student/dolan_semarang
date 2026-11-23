<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThreadResource\Pages;
use App\Models\Thread;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ThreadResource extends Resource
{
    protected static ?string $model = Thread::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Forum Komunitas';

    protected static ?string $modelLabel = 'thread';

    protected static \UnitEnum|string|null $navigationGroup = 'Konten';

    public static function getNavigationBadge(): ?string
    {
        return (string) Thread::count();
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')->label('Judul Thread')->required()->maxLength(200),
                Select::make('user_id')->label('Penulis')
                    ->relationship('user', 'name')->searchable()->preload()->required(),
                Select::make('type')->label('Jenis')
                    ->options(['tanya' => 'Tanya', 'cerita' => 'Cerita'])
                    ->default('tanya')->required(),
                Textarea::make('body')->label('Isi')->rows(4),
                TextInput::make('reply_count')->label('Jumlah Balasan')->numeric()->default(0)->min(0),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->sortable()->limit(50)
                    ->description(fn ($record) => $record->user?->name),
                TextColumn::make('type')->label('Jenis')->badge()
                    ->formatStateUsing(fn ($state) => $state === 'tanya' ? 'Tanya' : 'Cerita')
                    ->color(fn ($state) => $state === 'tanya' ? 'info' : 'success'),
                TextColumn::make('reply_count')->label('Balasan')->sortable(),
                TextColumn::make('last_activity_at')->label('Aktivitas Terakhir')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->label('Jenis')->options(['tanya' => 'Tanya', 'cerita' => 'Cerita']),
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
            ->defaultSort('last_activity_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListThreads::route('/'),
            'create' => Pages\CreateThread::route('/create'),
            'edit' => Pages\EditThread::route('/{record}/edit'),
        ];
    }
}
