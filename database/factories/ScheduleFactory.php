<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\TeachingAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startHour = fake()->numberBetween(7, 13);
        $startMinute = fake()->randomElement([0, 15, 30, 45]);

        $start = sprintf('%02d:%02d:00', $startHour, $startMinute);

        $endHour = min($startHour + fake()->randomElement([1, 1, 2]), 16);
        $end = sprintf('%02d:%02d:00', $endHour, $startMinute);

        return [
            'teaching_assignment_id' => TeachingAssignment::factory(),

            // Jum'at (5) tidak dijadikan default karena merupakan
            // hari libur rutin sekolah.
            'day_of_week' => fake()->randomElement([
                1, 2, 3, 4, 6,
            ]),

            'start_time' => $start,
            'end_time' => $end,
            'room' => fake()->optional()->bothify('R.##'),
        ];
    }
}
