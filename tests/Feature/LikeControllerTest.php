<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_likes_of_a_user()
    {
        // Tạo người dùng
        $user = User::factory()->create();

        // Tạo 3 like liên kết với người dùng
        Like::factory()->count(3)->create(['user_id' => $user->id]);

        // Gửi request để lấy tất cả like của người dùng
        $response = $this->getJson("/api/get-all-like/{$user->id}");

        // Kiểm tra response
        $response->assertStatus(200)
            ->assertJsonCount(3); // Kiểm tra số lượng like trả về
    }

    /** @test */
    public function it_can_insert_a_like()
    {
        // Tạo người dùng và bài viết
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Gửi request để chèn like
        $response = $this->postJson('/api/insert-like', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        // Kiểm tra response
        $response->assertStatus(201); // Kiểm tra mã trạng thái 201 (Created)
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]); // Kiểm tra dữ liệu đã được thêm vào cơ sở dữ liệu
    }

    /** @test */
    public function it_can_delete_a_like()
    {
        // Tạo người dùng và bài viết
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Tạo like liên kết với người dùng và bài viết
        $like = Like::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);

        // Gửi request để xóa like
        $response = $this->deleteJson("/api/delete-like/{$post->id}");

        // Kiểm tra response
        $response->assertStatus(204); // Kiểm tra mã trạng thái 204 (No Content)
        $this->assertDatabaseMissing('likes', [
            'id' => $like->id,
        ]); // Kiểm tra dữ liệu đã bị xóa khỏi cơ sở dữ liệu
    }
}
