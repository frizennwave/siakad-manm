<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\SchoolCalendar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolCalendar>
 */
class SchoolCalendarFactory extends Factory
{
    protected $model = SchoolCalendar::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'date' => fake()->dateTimeBetween('2026-01-01', '2026-12-31'),
            'type' => fake()->randomElement([
                'national_holiday',
                'school_holiday',
            ]),
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
        ];
    }

    public function nationalHoliday(): static
    {
        return $this->state([
            'type' => 'national_holiday',
        ]);
    }

    public function schoolHoliday(): static
    {
        return $this->state([
            'type' => 'school_holiday',
        ]);
    }
}
