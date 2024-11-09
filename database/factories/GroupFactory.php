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
            'title' => $this->faker->word,
            'image_group' => $this->faker->imageUrl(),
            'state' => $this->faker->boolean,
        ];
    }
}
