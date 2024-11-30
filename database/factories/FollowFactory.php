<?php

namespace Database\Factories;

namespace Database\Factories;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowFactory extends Factory
{
    protected $model = Follow::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Người dùng được theo dõi
            'follower' => User::factory(), // Người theo dõi
        ];
    }
}
