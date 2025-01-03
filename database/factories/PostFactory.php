<?php


namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'description' => $this->faker->text,
            'user_id' => User::factory(), // Create a user for each post
            'detail_group_user_id' => null, // You can set this to a group ID if needed
        ];
    }
}
