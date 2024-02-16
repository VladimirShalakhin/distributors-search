<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Region>
 */
class RegionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regionName = fake()->country().fake()->text();
        $county = fake()->country().fake()->text();

        return [
            'internal_id' => fake()->randomDigitNotNull,
            'region_name' => $regionName,
            'county' => $county,
        ];
    }
}
