<?php

namespace App\Filament\Dashboard\Resources\Students\Pages;

use App\Filament\Dashboard\Resources\Students\StudentResource;
use App\Models\Student;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManageStudents extends ManageRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Data Siswa')
                ->using(function (array $data): Model {
                    return DB::transaction(function () use ($data): Student {
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => $data['password'],
                            'email_verified_at' => null,
                        ]);

                        $user->assignRole('student');

                        return Student::create([
                            'user_id' => $user->id,
                            'nis' => $data['nis'],
                        ]);
                    });
                }),
        ];
    }
}
