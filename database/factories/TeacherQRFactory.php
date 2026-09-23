<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\TeacherQR;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<TeacherQR>
 */
class TeacherQRFactory extends Factory
{
    protected $model = TeacherQR::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'token' => Str::random(64),
            'expired_at' => Carbon::now()->addMinutes(5),
        ];
    }
}
