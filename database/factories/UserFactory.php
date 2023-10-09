<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random number between 1 and 10 with varying probabilities
        $randomOfficeId = $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $department = ['Office of the Municipal Mayor', 'Municipal Accounting Office', 'Municipal Agriculture Office', 'Municipal Assessors Office', 'Municipal Budget Office'];
        $randomDepartment = $this->faker->randomElement($department);
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'username'=>fake()->unique()->name(),
            // 'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => 0,
            'department' => $randomDepartment,
            'office_id' => $randomOfficeId,
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
