<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name, // Tạo tên ngẫu nhiên cho tác giả
            'born' => $this->faker->date(), // Ngày sinh ngẫu nhiên
            'dob' => $this->faker->date(), // Ngày sinh ngẫu nhiên
            'died' => $this->faker->optional()->date(), // Ngày mất (tùy chọn)
            'description' => $this->faker->paragraph, // Mô tả ngẫu nhiên
            'image' => 'default.jpg', // Đặt một hình ảnh mặc định
        ];
    }
}
