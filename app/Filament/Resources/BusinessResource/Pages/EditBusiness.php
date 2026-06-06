<?php

namespace App\Filament\Resources\BusinessResource\Pages;

use App\Filament\Resources\BusinessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBusiness extends EditRecord
{
    protected static string $resource = BusinessResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['gallery_images'] = $this->record->images()->pluck('path')->toArray();
        $data['has_location'] = !empty($data['latitude']) || !empty($data['longitude']);
        return $data;
    }

    protected function afterSave(): void
    {
        $paths = array_values($this->data['gallery_images'] ?? []);
        $this->record->images()->delete();
        foreach ($paths as $index => $path) {
            $this->record->images()->create(['path' => $path, 'order' => $index]);
        }
    }
}
