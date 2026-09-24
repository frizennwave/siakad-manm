<?php

namespace App\Filament\Dashboard\Resources\Majors\Pages;

use App\Filament\Dashboard\Resources\Majors\MajorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMajors extends ManageRecords
{
    protected static string $resource = MajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Jurusan Baru')
                ->successNotificationTitle('Jurusan baru berhasil ditambahkan!'),
        ];
    }
}
