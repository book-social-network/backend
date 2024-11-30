<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    /** @test */
    public function it_can_get_all_users()
    {
        // Tạo một vài người dùng mẫu
        User::factory()->count(3)->create();

        // Lấy tất cả người dùng
        $users = $this->userRepository->getAllUsers();

        // Kiểm tra nếu có ít nhất 3 người dùng
        $this->assertCount(3, $users);
    }

    /** @test */
    public function it_can_get_user_by_id()
    {
        // Tạo người dùng mẫu
        $user = User::factory()->create();

        // Lấy người dùng theo id
        $foundUser = $this->userRepository->getUser($user->id);

        // Kiểm tra xem người dùng có đúng không
        $this->assertEquals($user->id, $foundUser->id);
    }

    /** @test */
    public function it_can_get_user_by_email()
    {
        // Tạo người dùng mẫu
        $user = User::factory()->create(['email' => 'test@example.com']);

        // Lấy người dùng theo email
        $foundUser = $this->userRepository->getUserByEmail('test@example.com');

        // Kiểm tra xem người dùng có đúng không
        $this->assertEquals('test@example.com', $foundUser->email);
    }

    /** @test */
    public function it_can_insert_user()
    {
        // Dữ liệu để tạo người dùng mới
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password')
        ];

        // Chèn người dùng vào cơ sở dữ liệu
        $newUser = $this->userRepository->insertUser($data);

        // Kiểm tra nếu người dùng mới đã được chèn vào cơ sở dữ liệu
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
    }

    /** @test */
    public function it_can_update_user()
    {
        // Tạo người dùng mẫu
        $user = User::factory()->create();

        // Dữ liệu để cập nhật người dùng
        $data = [
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'password' => bcrypt('newpassword')
        ];

        // Cập nhật người dùng
        $this->userRepository->updateUser($data, $user->id);

        // Kiểm tra xem người dùng đã được cập nhật
        $this->assertDatabaseHas('users', ['email' => 'janedoe@example.com']);
        $this->assertDatabaseMissing('users', ['email' => $user->email]);
    }

    /** @test */
    public function it_can_delete_user()
    {
        // Tạo người dùng mẫu
        $user = User::factory()->create();

        // Xóa người dùng
        $this->userRepository->deleteUser($user->id);

        // Kiểm tra xem người dùng đã bị xóa khỏi cơ sở dữ liệu
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_get_users_by_name()
    {
        // Tạo người dùng mẫu
        $user = User::factory()->create(['name' => 'John Doe']);

        // Lấy người dùng theo tên
        $users = $this->userRepository->getByName('John');

        // Kiểm tra nếu có người dùng với tên 'John'
        $this->assertCount(1, $users);
        $this->assertEquals('John Doe', $users[0]->name);
    }
}
