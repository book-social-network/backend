<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Follow;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper function to get the authentication token
     */
    private function getAuthToken()
    {
        // Đăng nhập và lấy token
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',  // Thay đổi thành email hợp lệ của bạn
            'password' => 'password123'       // Thay đổi mật khẩu nếu cần
        ]);

        $token = $response->json('access_token');
        $this->assertNotNull($token, 'Token không tồn tại trong response');

        return $token;
    }

    /**
     * Test getAllFollow - Lấy tất cả người theo dõi của người dùng
     */
    public function testGetAllFollow()
    {
        // Lấy token thông qua phương thức đăng nhập
        $token = $this->getAuthToken();

        // Đăng nhập với token
        $this->withHeaders([
            'Authorization' => "Bearer $token"
        ]);

        // Tạo người dùng và follow một số người
        $user = User::factory()->create();
        $followers = Follow::factory()->count(5)->create(['user_id' => $user->id]);

        // Gửi yêu cầu đến API lấy tất cả người theo dõi
        $response = $this->getJson('/api/follow/get-all');

        // Kiểm tra phản hồi trả về có đúng mã trạng thái 200
        $response->assertStatus(200);

        // Kiểm tra cấu trúc JSON trả về
        $response->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'followers' => ['*' => ['id', 'user_id', 'follower']]
        ]);

        // Kiểm tra danh sách followers có đúng 5 bản ghi
        $response->assertJsonCount(5, 'followers');
    }

    /**
     * Test handleFollow - Xử lý theo dõi một người dùng
     */
    public function testHandleFollow()
    {
        // Lấy token thông qua phương thức đăng nhập
        $token = $this->getAuthToken();

        // Đăng nhập với token
        $this->withHeaders([
            'Authorization' => "Bearer $token"
        ]);

        // Tạo người dùng và người được theo dõi
        $user = User::factory()->create();
        $followUser = User::factory()->create();

        // Gửi yêu cầu follow người dùng
        $response = $this->postJson('/api/follow', [
            'user_id' => $followUser->id
        ]);

        // Kiểm tra phản hồi trả về có đúng mã trạng thái 201 (Created)
        $response->assertStatus(201);

        // Kiểm tra đã follow thành công
        $response->assertJson([
            'message' => 'Đã theo dõi thành công.'
        ]);
    }

    /**
     * Test handleUnfollow - Xử lý bỏ theo dõi một người dùng
     */
    public function testHandleUnfollow()
    {
        // Lấy token thông qua phương thức đăng nhập
        $token = $this->getAuthToken();

        // Đăng nhập với token
        $this->withHeaders([
            'Authorization' => "Bearer $token"
        ]);

        // Tạo người dùng và người được theo dõi
        $user = User::factory()->create();
        $followUser = User::factory()->create();

        // Follow trước khi bỏ theo dõi
        $follow = Follow::create([
            'user_id' => $user->id,
            'follower' => $followUser->id
        ]);

        // Gửi yêu cầu bỏ theo dõi người dùng
        $response = $this->deleteJson('/api/follow', [
            'user_id' => $followUser->id
        ]);

        // Kiểm tra phản hồi trả về có đúng mã trạng thái 200
        $response->assertStatus(200);

        // Kiểm tra đã bỏ theo dõi thành công
        $response->assertJson([
            'message' => 'Đã bỏ theo dõi thành công.'
        ]);
    }

    /**
     * Test suggestFriends - Đề xuất bạn bè cho người dùng
     */
    public function testSuggestFriends()
    {
        // Lấy token thông qua phương thức đăng nhập
        $token = $this->getAuthToken();

        // Đăng nhập với token
        $this->withHeaders([
            'Authorization' => "Bearer $token"
        ]);

        // Tạo người dùng và một số người bạn để gợi ý
        $user = User::factory()->create();
        $suggestedFriends = User::factory()->count(5)->create();

        // Gửi yêu cầu đề xuất bạn bè
        $response = $this->getJson('/api/follow/suggest-friends');

        // Kiểm tra phản hồi trả về có đúng mã trạng thái 200
        $response->assertStatus(200);

        // Kiểm tra cấu trúc JSON trả về
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'email']
        ]);

        // Kiểm tra danh sách bạn bè gợi ý có đúng 5 bản ghi
        $response->assertJsonCount(5);
    }
}
