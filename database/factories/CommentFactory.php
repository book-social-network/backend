<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Tạo user mẫu
            'post_id' => Post::factory(), // Tạo post mẫu
            'description' => $this->faker->sentence, // Sinh nội dung comment ngẫu nhiên
        ];
    }
}
