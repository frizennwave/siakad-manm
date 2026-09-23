<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->teacher(),
            'nip' => fake()->unique()->numerify('19##############'),
            'gender' => fake()->randomElement(['laki-laki', 'perempuan']),
            'position' => 'Guru',
            'address' => fake()->address(),
            'joined_at' => fake()->dateTimeBetween('-10 years', '-1 year'),
            'status' => true,
        ];
    }

    public function tendik(): static
    {
        return $this->state([
            'position' => 'Tenaga Kependidikan',
        ]);
    }
}
