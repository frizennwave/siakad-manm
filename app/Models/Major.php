<?php

namespace App\Models;

use Database\Factories\MajorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

#[Fillable(['code', 'name', 'status'])]
class Major extends Model
{
    /** @use HasFactory<MajorFactory> */
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        parent::casts();

        return [
            'status' => 'boolean',
        ];
    }

    /**
     * @return HasMany<ClassRoom, $this>
     */
    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }
}
