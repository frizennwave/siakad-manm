<?php

namespace App\Filament\Dashboard\Resources\Subjects\Pages;

use App\Filament\Dashboard\Resources\Subjects\SubjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSubjects extends ManageRecords
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Mata Pelajaran Baru')
                ->successNotificationTitle('Mata pelajaran baru berhasil ditambahkan!'),
        ];
    }
}
