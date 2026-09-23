<?php

namespace App\Models;

use Database\Factories\ClassRoomFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

#[Fillable(['academic_year_id', 'major_id', 'grade_level', 'name'])]
class ClassRoom extends Model
{
    /** @use HasFactory<ClassRoomFactory> */
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        return [
            'grade_level' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<AcademicYear, $this>
     */
    public function academic_year(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * @return BelongsTo<Major, $this>
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    /**
     * @return HasMany<StudentEnrollment, $this>
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    /**
     * @return HasMany<TeachingAssignment, $this>
     */
    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class);
    }
}
