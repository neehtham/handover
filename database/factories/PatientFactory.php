<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'id_no' => fake()->unique()->numerify('A#######'),
            'diagnosis' => fake()->sentence(),
            'bed_number' => fake()->bothify('Bed ##'),
            'type' => fake()->randomElement(['chronic', 'post_op']),
            'is_discharged' => false,
        ];
    }
}
