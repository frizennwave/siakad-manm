<?php

namespace App\Filament\Dashboard\Resources\AcademicYears\Pages;

use App\Filament\Dashboard\Resources\AcademicYears\AcademicYearResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAcademicYears extends ManageRecords
{
    protected static string $resource = AcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tahun Ajaran Baru')
                ->successNotificationTitle('Tahun ajaran baru berhasil ditambahkan!'),
        ];
    }
}
