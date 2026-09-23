<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeachingAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeachingAssignment>
 */
class TeachingAssignmentFactory extends Factory
{
    protected $model = TeachingAssignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'subject_id' => Subject::factory(),
            'class_room_id' => ClassRoom::factory(),
            'semester' => fake()->randomElement([1, 2]),
        ];
    }

    public function semesterOne(): static
    {
        return $this->state([
            'semester' => 1,
        ]);
    }

    public function semesterTwo(): static
    {
        return $this->state([
            'semester' => 2,
        ]);
    }
}
