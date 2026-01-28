<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\User;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;

class RecentActivity extends BaseWidget
{
    protected static ?string $heading = 'Recent Platform Activity';
    
    protected static ?int $sort = 4;

    protected function getTableQuery(): Builder
    {
        // Combined query for new users and big invoices? 
        // Better to just show two separate widgets or one for "System Events"
        // Let's show recent high-value invoices as a priority.
        return Invoice::query()->where('amount', '>', 1000)->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user.name')->label('Freelancer'),
            Tables\Columns\TextColumn::make('client_name')->label('Client'),
            Tables\Columns\TextColumn::make('amount')->money('USD')->color('success')->weight('bold'),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'success' => 'Paid',
                    'warning' => 'Pending',
                    'danger' => 'Overdue',
                ]),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Time'),
        ];
    }
}
