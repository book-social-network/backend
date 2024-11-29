<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;

class LikeFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Tự động tạo User liên quan
            'post_id' => Post::factory(), // Tự động tạo Post liên quan
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
