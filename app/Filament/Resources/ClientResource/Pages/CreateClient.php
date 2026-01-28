<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['id'])) {
            $data['id'] = (string) Str::uuid();
        }
        
        // Default to first user if none selected or if we want to auto-assign
        if (empty($data['user_id'])) {
             $data['user_id'] = auth()->id();
        }

        return $data;
    }
}
