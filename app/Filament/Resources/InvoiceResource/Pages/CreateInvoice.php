<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

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
