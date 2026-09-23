<?php

namespace Database\Factories;

use App\Models\AbsenceLetter;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AbsenceLetter>
 */
class AbsenceLetterFactory extends Factory
{
    protected $model = AbsenceLetter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'verified_by' => null,

            'type' => fake()->randomElement([
                'sick',
                'permission',
            ]),

            'reason' => fake()->sentence(),

            'status' => 'pending',

            'qr_token' => null,

            'verified_at' => null,
            'issued_at' => null,
        ];
    }

    public function sick(): static
    {
        return $this->state([
            'type' => 'sick',
        ]);
    }

    public function permission(): static
    {
        return $this->state([
            'type' => 'permission',
        ]);
    }

    public function approved(User $admin): static
    {
        return $this->state([
            'verified_by' => $admin->id,
            'status' => 'approved',
            'verified_at' => now(),
        ]);
    }

    public function issued(User $admin): static
    {
        return $this->state([
            'verified_by' => $admin->id,
            'status' => 'issued',
            'qr_token' => Str::random(64),
            'verified_at' => now(),
            'issued_at' => now(),
        ]);
    }
}
