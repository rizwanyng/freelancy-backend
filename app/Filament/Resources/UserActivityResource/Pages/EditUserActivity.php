<?php

namespace App\Filament\Resources\UserActivityResource\Pages;

use App\Filament\Resources\UserActivityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserActivity extends EditRecord
{
    protected static string $resource = UserActivityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
