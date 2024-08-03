<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tickets', Ticket::count()),
            Stat::make('Total Categories', Category::active()->count()),
            Stat::make('Total Agent', User::whereHas('roles', function ($query) {
                $query->where('title', 'Agent');
            })->count()),
        ];
    }
}
