<?php

namespace App\Filament\Resources\AdminNotificationResource\Pages;

use App\Filament\Resources\AdminNotificationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminNotification extends EditRecord
{
    protected static string $resource = AdminNotificationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
