<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->sentence,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'), // Ngày tạo
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'), // Ngày cập nhật
        ];
    }
}
