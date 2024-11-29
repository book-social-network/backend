<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Book;

class ShareFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Tự động tạo người dùng liên quan
            'book_id' => Book::factory(), // Tự động tạo sách liên quan
            'link_share' => $this->faker->url, // Tạo đường dẫn chia sẻ ngẫu nhiên
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
