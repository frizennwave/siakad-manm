<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

#[Fillable(['teacher_id', 'token', 'expired_at'])]
class TeacherQR extends Model
{
    use HasFactory;

    protected $table = 'teacher_qrs';

    #[Override]
    protected function casts(): array
    {
        parent::casts();

        return [
            'expired_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Teacher, $this>
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
