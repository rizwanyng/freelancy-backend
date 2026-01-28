<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalRevenue = Invoice::where('status', 'Paid')->sum('amount');
        $totalUsers = User::count();
        $totalProjects = Project::count();

        return [
            Card::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Across all users')
                ->descriptionIcon('heroicon-s-cash')
                ->color('success'),
            Card::make('Total Users', $totalUsers)
                ->description('Registered freelancers')
                ->descriptionIcon('heroicon-s-users')
                ->color('primary'),
            Card::make('Active Projects', $totalProjects)
                ->description('Work happening now')
                ->descriptionIcon('heroicon-s-briefcase')
                ->color('warning'),
        ];
    }
}
