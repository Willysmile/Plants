<?php

namespace Database\Factories;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'scientific_name' => $this->faker->optional()->sentence(3),
            'watering_frequency' => $this->faker->numberBetween(1, 5),
            'light_requirement' => $this->faker->numberBetween(1, 4),
            'description' => $this->faker->optional()->text(100),
            'temperature_min' => $this->faker->optional()->numberBetween(5, 15),
            'temperature_max' => $this->faker->optional()->numberBetween(20, 30),
            'humidity_level' => $this->faker->optional()->numberBetween(40, 80),
            'is_favorite' => false,
            'is_archived' => false,
        ];
    }
}
