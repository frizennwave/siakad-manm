<?php

namespace App\Filament\Dashboard\Resources\Teachers\Pages;

use App\Filament\Dashboard\Resources\Teachers\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManageTeachers extends ManageRecords
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Data Guru')
                ->using(function (array $data): Model {
                    return DB::transaction(function () use ($data): Teacher {
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => $data['password'],
                            'email_verified_at' => null,
                        ]);

                        $user->assignRole('teacher');

                        // $user->sendEmailVerificationNotification();

                        return Teacher::create([
                            'user_id' => $user->id,
                            'position' => $data['position'],
                            'status' => $data['status'],
                        ]);
                    });
                }),
        ];
    }
}
