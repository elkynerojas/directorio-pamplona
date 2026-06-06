<?php

namespace App\Filament\Resources\BusinessResource\Pages;

use App\Filament\Resources\BusinessResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBusiness extends CreateRecord
{
    protected static string $resource = BusinessResource::class;

    protected function afterCreate(): void
    {
        $paths = array_values($this->data['gallery_images'] ?? []);
        foreach ($paths as $index => $path) {
            $this->record->images()->create(['path' => $path, 'order' => $index]);
        }
    }
}
