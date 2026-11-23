<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Destination;
use App\Models\Review;
use App\Models\Thread;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DolanStats extends BaseWidget
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected function getStats(): array
    {
        $avgRating = Review::query()
            ->selectRaw('avg((score_view+score_access+score_cleanliness+score_facility)/4.0) as r')
            ->value('r');

        return [
            Stat::make('Total Destinasi', Destination::count())
                ->description('Termasuk '.Destination::where('is_featured', true)->count().' unggulan')
                ->icon('heroicon-o-map-pin')
                ->color('primary'),

            Stat::make('Total Review', Review::count())
                ->description('Rating rata-rata '.number_format((float) $avgRating, 1).' / 5')
                ->icon('heroicon-o-star')
                ->color('warning'),

            Stat::make('Pengguna Terdaftar', User::count())
                ->description(User::where('is_admin', true)->count().' admin panel')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Thread Komunitas', Thread::count())
                ->description(Article::count().' artikel panduan')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('info'),
        ];
    }
}
