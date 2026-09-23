<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentEnrollment>
 */
class StudentEnrollmentFactory extends Factory
{
    protected $model = StudentEnrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'class_room_id' => ClassRoom::factory(),
            'start_date' => now()->startOfYear(),
            'end_date' => null,
            'status' => 'active',
        ];
    }

    public function active(): static
    {
        return $this->state([
            'status' => 'active',
            'end_date' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state([
            'status' => 'completed',
            'end_date' => now()->subMonths(2),
        ]);
    }

    public function graduated(): static
    {
        return $this->state([
            'status' => 'graduated',
            'end_date' => now()->subMonths(2),
        ]);
    }

    public function transferred(): static
    {
        return $this->state([
            'status' => 'transferred',
            'end_date' => now()->subMonths(1),
        ]);
    }
}
