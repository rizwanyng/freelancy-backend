<?php

namespace App\Filament\Resources\UserActivityResource\Pages;

use App\Filament\Resources\UserActivityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserActivities extends ListRecords
{
    protected static string $resource = UserActivityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
