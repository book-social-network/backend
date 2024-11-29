<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition()
    {
        return [
            'description' => $this->faker->paragraph,
            'star' => $this->faker->numberBetween(1, 5),
            'state_read' => $this->faker->randomElement([
                1, // 'Want to Read'
                2, // 'Reading'
                3, // 'Read'
            ]),
            'book_id' => Book::factory(),
            'user_id' => User::factory(),
        ];
    }
}
