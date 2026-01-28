<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\LineChartWidget;
use Carbon\Carbon;

class RevenueChart extends LineChartWidget
{
    protected static ?string $heading = 'Global Revenue (Last 30 Days)';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = collect(range(0, 30))->map(function ($days) {
            $date = Carbon::now()->subDays(30 - $days);
            $sum = Invoice::where('status', 'Paid')
                ->whereDate('updated_at', $date->toDateString())
                ->sum('amount');
                
            return [
                'label' => $date->format('M d'),
                'value' => $sum,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Revenue ($)',
                    'data' => $data->pluck('value')->toArray(),
                    'borderColor' => '#10B981',
                ],
            ],
            'labels' => $data->pluck('label')->toArray(),
        ];
    }
}
