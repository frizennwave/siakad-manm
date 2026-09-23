<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

#[Fillable(['teaching_assignment_id', 'day_of_week', 'start_time', 'end_time'])]
class Schedule extends Model
{
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        parent::casts();

        return [
            'day_of_week' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<TeachingAssignment, $this>
     */
    public function teachingAssignment(): BelongsTo
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    /**
     * @return HasMany<LessonSession, $this>
     */
    public function lessonSessions(): HasMany
    {
        return $this->hasMany(LessonSession::class);
    }
}
