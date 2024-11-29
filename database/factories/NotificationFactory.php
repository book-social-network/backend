<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    public function definition()
    {
        return [
            'to_id' => fake()->randomNumber(5, true), // ID của người nhận
            'to_type' => 'member',                  // Loại người nhận, mặc định là 'member'
            'from_id' => fake()->randomNumber(5, true), // ID của người gửi
            'from_type' => fake()->randomElement(['post', 'group', 'member']), // Loại người gửi
            'information' => fake()->sentence(10),  // Nội dung thông báo
            'state' => fake()->randomElement([0, 1]), // Trạng thái thông báo (0: chưa đọc, 1: đã đọc)
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'), // Ngày tạo
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'), // Ngày cập nhật
        ];
    }
}
