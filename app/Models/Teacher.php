<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Override;

#[Fillable(['user_id', 'nip', 'gender', 'position', 'address', 'joined_at', 'status'])]
class Teacher extends Model
{
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
            'status' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<TeachingAssignment, $this>
     */
    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class);
    }

    /**
     * @return HasOne<TeacherQR, $this>
     */
    public function teacherQr(): HasOne
    {
        return $this->hasOne(TeacherQR::class);
    }

    /**
     * @return HasMany<TeacherAttendance, $this>
     */
    public function teacherAttendances(): HasMany
    {
        return $this->hasMany(TeacherAttendance::class);
    }
}
