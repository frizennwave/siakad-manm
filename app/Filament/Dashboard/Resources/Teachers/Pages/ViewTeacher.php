<?php

namespace App\Filament\Dashboard\Resources\Teachers\Pages;

use App\Filament\Dashboard\Resources\Teachers\TeacherResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Override;

class ViewTeacher extends ViewRecord
{
    protected static string $resource = TeacherResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
