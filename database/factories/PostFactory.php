<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\DetailGroupUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Định nghĩa mô hình mẫu cho bài viết.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'detail_group_user_id' => DetailGroupUser::factory(),  // Tạo ngẫu nhiên một 'DetailGroupUser'
            'user_id' => User::factory(),  // Tạo ngẫu nhiên một 'User'
            'description' => $this->faker->paragraph,  // Tạo mô tả ngẫu nhiên cho bài viết
        ];
    }
}
