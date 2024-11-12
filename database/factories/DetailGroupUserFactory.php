<?php

namespace Database\Factories;

use App\Models\DetailGroupUser;
use App\Models\Group;  // Nếu bạn có mô hình Group
use App\Models\User;   // Nếu bạn có mô hình User
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailGroupUserFactory extends Factory
{
    protected $model = DetailGroupUser::class;

    /**
     * Định nghĩa mô hình mẫu cho DetailGroupUser.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group_id' => Group::factory(),  // Tạo ngẫu nhiên một Group
            'user_id' => User::factory(),    // Tạo ngẫu nhiên một User
            'state' => $this->faker->randomElement(['active', 'inactive', 'pending']),  // Tạo trạng thái ngẫu nhiên
            'role' => $this->faker->randomElement(['admin', 'member', 'moderator']), // Tạo vai trò ngẫu nhiên
        ];
    }
}
