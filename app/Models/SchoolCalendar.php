<?php

namespace App\Models;

use Database\Factories\SchoolCalendarFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

#[Fillable(['academic_year_id', 'date', 'type', 'name', 'description'])]
class SchoolCalendar extends Model
{
    /** @use HasFactory<SchoolCalendarFactory> */
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        parent::casts();

        return [
            'date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<AcademicYear, $this>
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
