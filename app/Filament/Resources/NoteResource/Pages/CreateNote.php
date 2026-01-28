<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Filament\Resources\NoteResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateNote extends CreateRecord
{
    protected static string $resource = NoteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['id'])) {
            $data['id'] = (string) Str::uuid();
        }
        if (empty($data['user_id'])) {
             $data['user_id'] = auth()->id();
        }
        return $data;
    }
}
