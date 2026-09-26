<?php

namespace App\Filament\Widgets;

use App\Models\Banner;
use App\Models\News;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $countNews = News::count();
        $countUser = User::authors()->count();
        $countBanner = Banner::count();
        return [
            Stat::make('Jumlah Berita', $countNews),
            Stat::make('Jumlah Author', $countUser, 'Author'),
            Stat::make('Jumlah Banner aktif', $countBanner),
        ];
    }
}
