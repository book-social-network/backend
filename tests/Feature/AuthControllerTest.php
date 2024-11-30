<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        Storage::fake('avatars');

        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'image_url',
                ]
            ]);
    }

    public function test_login_with_invalid_credentials()
    {
        $data = [
            'email' => 'wrongemail@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthorized',
            ]);
    }
    protected $token;

    // Hàm login để gửi yêu cầu đăng nhập
    public function test_login($email = null, $password = 'password')
    {
        // Nếu không truyền email, tạo một user ngẫu nhiên
        $email = $email ?? User::factory()->create()->email;

        // Gửi request đăng nhập
        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        // Kiểm tra response trả về có mã trạng thái 200
        $response->assertStatus(200);

        return $response;
    }

    // Hàm lấy token từ response của login
    public function test_getToken($email = null, $password = 'password')
    {
        // Đăng nhập và lấy response
        $response = $this->test_login($email, $password);

        // Lấy token từ response
        $token = $response->json('access_token');

        // Kiểm tra token có tồn tại trong response
        $this->assertNotNull($token, 'Token không tồn tại trong response');

        // Lưu token vào config để sử dụng trong các test khác
        $this->app['config']->set('test.token', $token);

        return $token;
    }
}
