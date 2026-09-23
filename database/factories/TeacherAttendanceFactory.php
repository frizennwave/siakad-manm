<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeacherAttendance>
 */
class TeacherAttendanceFactory extends Factory
{
    protected $model = TeacherAttendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'date' => fake()->dateTimeBetween('2026-09-01', '2026-12-31'),
            'scan_time' => '06:45:00',
            'status' => 'present',
        ];
    }

    public function late(): static
    {
        return $this->state([
            'status' => 'late',
            'scan_time' => '07:30:00',
        ]);
    }
}
