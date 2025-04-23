<?php

namespace Database\Factories;

use App\Models\ITCourse;
use Illuminate\Database\Eloquent\Factories\Factory;

class ITCourseFactory extends Factory
{
    protected $model = ITCourse::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'code' => strtoupper($this->faker->bothify('???###')),
            'category' => $this->faker->randomElement(ITCourse::getCategories()),
            'status' => $this->faker->randomElement(['active', 'inactive'])
        ];
    }
}
