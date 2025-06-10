<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Pages\Actions;
use App\Models\Epic;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function afterCreate(): void
    {
        $epicNames = ['Programming', 'Market', 'Socials', 'Sales', 'Juridics', 'Study'];

        foreach ($epicNames as $name) {
            Epic::create([
                'project_id' => $this->record->id,
                'starts_at' => now(),
                'ends_at' => now()->addYears(3),
                'name' => $name,
            ]);
        }
    }
}
