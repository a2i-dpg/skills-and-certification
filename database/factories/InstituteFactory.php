<?php

namespace Database\Factories;

use App\Models\Institute;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstituteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Institute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $instituteName = $this->faker->company;
        return [
            'title' => $instituteName,
            'code' => $this->faker->unique()->countryCode,
            'domain' => 'http://' . $this->faker->domainName,
            'address' => $this->faker->address,
        ];
    }
}
