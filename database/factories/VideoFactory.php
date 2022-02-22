<?php

namespace Database\Factories;

use App\Models\video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text(),
            'institute_id' => $this->faker->randomElement([1,2]),
            'video_category_id' => $this->faker->randomElement([1,2]),
            'video_type' => $this->faker->randomElement([1,2]),
            'youtube_video_url' => "https://youtu.be/RkgA0TJy280",
            'youtube_video_id' => "RkgA0TJy280",
        ];
    }
}
