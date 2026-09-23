<?php

namespace App\Models;

use Database\Factories\StudentQRFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['student_id', 'token'])]
class StudentQR extends Model
{
    /** @use HasFactory<StudentQRFactory> */
    use HasFactory;

    protected $table = 'student_qrs';

    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
