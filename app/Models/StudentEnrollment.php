<?php

namespace App\Models;

use Database\Factories\StudentEnrollmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;
use Override;

#[Fillable(['student_id', 'class_room_id', 'start_date', 'end_date', 'status'])]
class StudentEnrollment extends Model
{
    /** @use HasFactory<StudentEnrollmentFactory> */
    use HasFactory;

    #[Override]
    protected static function booted()
    {
        static::saving(function (StudentEnrollment $enrollment): void {
            if ($enrollment->status !== 'active') {
                return;
            }

            $hasAnotherActiveEnrollment = static::query()
                ->where('student_id', $enrollment->student_id)
                ->where('status', 'active')
                ->when(
                    $enrollment->exists,
                    fn ($query) => $query->whereKeyNot($enrollment->getKey())
                )
                ->exists();

            if ($hasAnotherActiveEnrollment) {
                throw ValidationException::withMessages([
                    'status' => 'Siswa masih memiliki enrollment aktif lain. '.
                    'Selesaikan enrollment aktif tersebut terlebih dahulu.',
                ]);
            }

        });
    }

    #[Override]
    protected function casts(): array
    {
        parent::casts();

        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return BelongsTo<ClassRoom, $this>
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
