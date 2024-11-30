<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Follow;
use App\Repositories\FollowRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $followRepository;

    // Khởi tạo FollowRepository trong phương thức setUp
    public function setUp(): void
    {
        parent::setUp();
        $this->followRepository = new FollowRepository();  // Tạo instance của FollowRepository
    }

    /**
     * Test user can follow another user.
     *
     * @return void
     */
    // Test getAllFollowOfUser method
    public function testGetAllFollowOfUser()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        Follow::factory()->create([
            'follower' => $user1->id,
            'user_id' => $user2->id,
        ]);
        Follow::factory()->create([
            'follower' => $user1->id,
            'user_id' => $user3->id,
        ]);
        Follow::factory()->create([
            'follower' => $user2->id,
            'user_id' => $user3->id,
        ]);

        // Gọi phương thức từ FollowRepository
        $follows = $this->followRepository->getAllFollowOfUser($user1->id);
        $this->assertCount(2, $follows); // Kiểm tra có 2 follow từ user1
    }

    // Test getAllUserFollow method
    public function testGetAllUserFollow()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Follow::factory()->create([
            'follower' => $user1->id,
            'user_id' => $user2->id,
        ]);

        // Gọi phương thức từ FollowRepository
        $follows = $this->followRepository->getAllUserFollow($user2->id);
        $this->assertCount(1, $follows); // Kiểm tra có 1 follow của user2
    }

    // Test getFollow method
    public function testGetFollow()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $follow = Follow::factory()->create([
            'follower' => $user1->id,
            'user_id' => $user2->id,
        ]);

        // Gọi phương thức từ FollowRepository
        $followResult = $this->followRepository->getFollow($user1->id, $user2->id);
        $this->assertNotNull($followResult); // Kiểm tra follow tồn tại
    }

    // Test insertFollow method
    public function testInsertFollow()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $data = [
            'follower' => $user1->id,
            'user_id' => $user2->id,
        ];

        // Gọi phương thức từ FollowRepository
        $follow = $this->followRepository->insertFollow($data);
        $this->assertDatabaseHas('follows', $data); // Kiểm tra dữ liệu có trong DB
    }

    // Test deleteFollow method
    public function testDeleteFollow()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $follow = Follow::factory()->create([
            'follower' => $user1->id,
            'user_id' => $user2->id,
        ]);

        // Gọi phương thức từ FollowRepository
        $this->followRepository->deleteFollow($follow->id);
        $this->assertDatabaseMissing('follows', ['id' => $follow->id]); // Kiểm tra follow đã bị xóa
    }
    /**
     * Test user can suggest friends.
     *
     * @return void
     */
    public function test_user_can_suggest_friends()
    {
        // Tạo người dùng và người bạn
        $user = User::factory()->create();
        User::factory(20)->create();
        $this->actingAs($user);
        $response = $this->getJson('/api/follow/suggest-friends');
        $response->assertStatus(200);

        // Kiểm tra số lượng bạn bè được đề xuất
        $response->assertJsonCount(20); // Kiểm tra có 20 người bạn được đề xuất
    }
}
