<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'from_id' => User::factory(), // Tự động tạo user cho from_id
            'to_id' => User::factory(),   // Tự động tạo user cho to_id
            'information' => $this->faker->sentence,
            'from_type' => 'user',        // Hoặc tùy giá trị bạn cần
            'to_type' => 'user',          // Hoặc tùy giá trị bạn cần
            'state' => $this->faker->randomElement([0, 1]),
        ];
    }
}
