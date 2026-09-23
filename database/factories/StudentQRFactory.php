<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\StudentQR;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<StudentQR>
 */
class StudentQRFactory extends Factory
{
    protected $model = StudentQR::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'token' => Str::random(64),
        ];
    }
}
