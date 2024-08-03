<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TicketOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Ticket Overview';

    public ?string $filter = 'week';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $start = null;
        $end = null;
        $perData = null;

        switch ($this->filter) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                $perData = 'perHour';
                break;
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $perData = 'perDay';
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $perData = 'perDay';
                break;
            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $perData = 'perMonth';
                break;
        }


        $data = Trend::model(Ticket::class)
        ->between(
            start: $start,
            end: $end,
        )
        ->$perData()
        ->count();
 
    return [
        'datasets' => [
            [
                'label' => 'Ticket',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
