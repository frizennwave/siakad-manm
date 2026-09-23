<?php

namespace App\Models;

use Database\Factories\AcademicYearFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

#[Fillable(['year', 'status'])]
class AcademicYear extends Model
{
    /** @use HasFactory<AcademicYearFactory> */
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        parent::casts();

        return [
            'year' => 'boolean',
        ];
    }

    /**
     * @return HasMany<ClassRoom, $this>
     */
    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }

    /**
     * @return HasMany<SchoolCalendar, $this>
     */
    public function schoolCalendars(): HasMany
    {
        return $this->hasMany(SchoolCalendar::class);
    }
}
