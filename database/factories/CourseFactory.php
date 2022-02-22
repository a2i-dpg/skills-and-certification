<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->sentence,
            'prerequisite' => $this->faker->word,
            'eligibility' => $this->faker->word,
            'cover_image' => $this->faker->image(),
            'code' => $this->faker->unique()->countryCode,
            'institute_id' => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10]),
            'row_status' => 1,
            'created_by' => null,
        ];
    }
}
