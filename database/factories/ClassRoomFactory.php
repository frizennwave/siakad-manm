<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Major;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    protected $model = ClassRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'major_id' => null,
            'grade_level' => fake()->numberBetween(10, 12),
            'name' => fake()->unique()->bothify('??-#'),
        ];
    }

    public function withMajor(?Major $major = null): static
    {
        return $this->state([
            'major_id' => $major->id ?? Major::factory(),
        ]);
    }
}
