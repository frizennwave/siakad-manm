<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    protected $model = AcademicYear::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startYear = fake()->unique()->numberBetween(2020, 2035);

        return [
            'year' => $startYear.'/'.($startYear + 1),
            'status' => false,
        ];
    }

    public function active(): static
    {
        return $this->state([
            'status' => true,
        ]);
    }

    public function closed(): static
    {
        return $this->state([
            'status' => false,
        ]);
    }
}
