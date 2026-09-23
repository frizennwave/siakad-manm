<?php

namespace App\Models;

use Database\Factories\AbsenceLetterFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

#[Fillable(['student_id', 'verified_by', 'type', 'reason', 'status', 'qr_token', 'verified_at', 'issued_at'])]
class AbsenceLetter extends Model
{
    /** @use HasFactory<AbsenceLetterFactory> */
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        parent::casts();

        return [
            'verified_at' => 'datetime',
            'issued_at' => 'datetime',
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
     * @return BelongsTo<User, $this>
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Attendance, $this>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
