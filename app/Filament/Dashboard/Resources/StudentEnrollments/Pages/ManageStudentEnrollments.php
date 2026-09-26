<?php

namespace App\Filament\Dashboard\Resources\StudentEnrollments\Pages;

use App\Filament\Dashboard\Resources\StudentEnrollments\StudentEnrollmentResource;
use App\Models\StudentEnrollment;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageStudentEnrollments extends ManageRecords
{
    protected static string $resource = StudentEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Enrollment')
                ->before(function (CreateAction $action, array $data): void {
                    $hasActiveEnrollment = StudentEnrollment::query()
                        ->where('student_id', $data['student_id'])
                        ->where('status', 'active')
                        ->exists();

                    if ($hasActiveEnrollment) {
                        Notification::make()
                            ->danger()
                            ->title('Enrollment tidak dapat ditambahkan')
                            ->body(
                                'Siswa ini masih memiliki enrollment aktif. '.
                                'Selesaikan enrollment sebelumnya terlebih dahulu.'
                            )
                            ->send();

                        $action->halt();
                    }
                }),
        ];
    }
}
