<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Artikel Panduan';

    protected static ?string $modelLabel = 'artikel';

    protected static \UnitEnum|string|null $navigationGroup = 'Konten';

    public static function getNavigationBadge(): ?string
    {
        return (string) Article::count();
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')->label('Judul')->required()->maxLength(200)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                TextInput::make('slug')->label('Slug URL')->required()->maxLength(220)->unique(ignoreRecord: true),
                Select::make('author_id')->label('Penulis')
                    ->relationship('author', 'name')->searchable()->preload()->required(),
                TextInput::make('tag')->label('Tag')->required()->placeholder('ITINERARY / TIPS / SEJARAH'),
                TextInput::make('cover_image')->label('Path Cover')->required()->helperText('Relatif ke public/, contoh: images/panduan-bromo.jpg'),
                Textarea::make('excerpt')->label('Ringkasan')->rows(2)->required(),
                TextInput::make('read_minutes')->label('Menit Baca')->numeric()->default(5)->min(1),
                TextInput::make('likes')->label('Suka')->numeric()->default(0),
                Toggle::make('is_featured')->label('Artikel Unggulan')->default(false),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->sortable()->limit(45),
                TextColumn::make('tag')->label('Tag')->badge()->color('primary')->sortable(),
                TextColumn::make('author.name')->label('Penulis')->searchable()->sortable(),
                TextColumn::make('read_minutes')->label('Baca (mnt)')->sortable(),
                TextColumn::make('likes')->label('Suka')->sortable(),
                ToggleColumn::make('is_featured')->label('Unggulan'),
                TextColumn::make('updated_at')->label('Diubah')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tag')->label('Tag')
                    ->options(fn () => Article::query()->distinct()->pluck('tag', 'tag')->all()),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Unggulan'),
            ])
            ->actions([
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
        ];
    }
}
