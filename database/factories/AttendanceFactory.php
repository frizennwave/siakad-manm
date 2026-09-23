<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\LessonSession;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lesson_session_id' => LessonSession::factory(),
            'student_id' => Student::factory(),

            'status' => 'present',

            'scan_time' => now(),

            'absence_letter_id' => null,
        ];
    }

    public function present(): static
    {
        return $this->state([
            'status' => 'present',
            'scan_time' => now(),
            'absence_letter_id' => null,
        ]);
    }

    public function sick(): static
    {
        return $this->state([
            'status' => 'sick',
            'scan_time' => null,
        ]);
    }

    public function permission(): static
    {
        return $this->state([
            'status' => 'permission',
            'scan_time' => null,
        ]);
    }

    public function absent(): static
    {
        return $this->state([
            'status' => 'absent',
            'scan_time' => null,
            'absence_letter_id' => null,
        ]);
    }
}
