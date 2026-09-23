<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Student;
use App\Models\TeachingAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Grade>
 */
class GradeFactory extends Factory
{
    protected $model = Grade::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'teaching_assignment_id' => TeachingAssignment::factory(),

            'type' => 'assignment',

            'assessment_name' => 'Tugas '.fake()->numberBetween(1, 10),

            'score' => fake()->randomFloat(2, 60, 100),
        ];
    }

    public function assignment(string $name = 'Tugas 1'): static
    {
        return $this->state([
            'type' => 'assignment',
            'assessment_name' => $name,
        ]);
    }

    public function midterm(): static
    {
        return $this->state([
            'type' => 'midterm',
            'assessment_name' => 'UTS',
        ]);
    }

    public function finalExam(): static
    {
        return $this->state([
            'type' => 'final',
            'assessment_name' => 'UAS',
        ]);
    }
}
