<?php

namespace App\Filament\Widgets;

use App\Models\Business;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Negocios', Business::count())
                ->icon('heroicon-o-building-storefront')
                ->color('success'),

            Stat::make('Negocios Activos', Business::where('is_active', true)->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Negocios Destacados', Business::where('is_featured', true)->count())
                ->icon('heroicon-o-star')
                ->color('warning'),

            Stat::make('Categorías', Category::where('is_active', true)->count())
                ->icon('heroicon-o-tag')
                ->color('info'),
        ];
    }
}
