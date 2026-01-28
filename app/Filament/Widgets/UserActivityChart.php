<?php

namespace App\Filament\Widgets;

use App\Models\UserActivity;
use Filament\Widgets\LineChartWidget;
use Carbon\Carbon;

class UserActivityChart extends LineChartWidget
{
    protected static ?string $heading = 'Daily Active Users (DAU)';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = collect(range(0, 30))->map(function ($days) {
            $date = Carbon::now()->subDays(30 - $days);
            $count = UserActivity::whereDate('date', $date->toDateString())->count();
                
            return [
                'label' => $date->format('M d'),
                'value' => $count,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $data->pluck('value')->toArray(),
                    'borderColor' => '#6366F1',
                ],
            ],
            'labels' => $data->pluck('label')->toArray(),
        ];
    }
}
