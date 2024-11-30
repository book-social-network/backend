<?php

namespace Database\Factories;

use App\Models\DetailGroupUser;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailGroupUserFactory extends Factory
{
    protected $model = DetailGroupUser::class;

    public function definition()
    {
        return [
            'group_id' => Group::factory(),  // Sử dụng factory của Group
            'user_id' => User::factory(),    // Sử dụng factory của User
            'role' => $this->faker->randomElement(['admin', 'member']),
            'state' => $this->faker->randomElement([0, 1]),  // 0 = muốn tham gia, 1 = đã tham gia
        ];
    }
}
