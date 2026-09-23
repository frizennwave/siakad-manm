<?php

namespace Database\Factories;

use App\Models\LessonSession;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LessonSession>
 */
class LessonSessionFactory extends Factory
{
    protected $model = LessonSession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'schedule_id' => Schedule::factory(),
            'session_date' => fake()->dateTimeBetween('2026-09-01', '2026-12-31'),
            'start_time' => '07:00:00',
            'end_time' => '08:30:00',
            'status' => 'scheduled',
            'cancelled_reason' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state([
            'status' => 'completed',
        ]);
    }

    public function cancelled(string $reason = 'Libur sekolah'): static
    {
        return $this->state([
            'status' => 'cancelled',
            'cancelled_reason' => $reason,
        ]);
    }
}
