<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'star' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->sentence,
        ];
    }
}
