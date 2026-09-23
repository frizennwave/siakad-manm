<?php

namespace App\Models;

use Database\Factories\AttendanceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

#[Fillable(['lesson_session_id', 'student_id', 'status', 'scan_time', 'absence_letter_id'])]
class Attendance extends Model
{
    /** @use HasFactory<AttendanceFactory> */
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        parent::casts();

        return [
            'scan_time' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<LessonSession, $this>
     */
    public function lessonSession(): BelongsTo
    {
        return $this->belongsTo(LessonSession::class);
    }

    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return BelongsTo<AbsenceLetter, $this>
     */
    public function absenceLetter(): BelongsTo
    {
        return $this->belongsTo(AbsenceLetter::class);
    }
}
