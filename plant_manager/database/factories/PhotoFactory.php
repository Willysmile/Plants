<?php

namespace Database\Factories;

use App\Models\Photo;
use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Photo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plant_id' => Plant::factory(),
            'filename' => 'plants/' . $this->faker->numberBetween(1, 100) . '/' . $this->faker->word() . '.jpg',
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
