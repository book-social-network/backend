<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test lấy tất cả bài viết.
     *
     * @test
     */
    public function it_can_get_all_posts()
    {
        Post::factory()->count(15)->create(); // Tạo 15 bài viết giả lập

        $response = $this->getJson('/api/get-all'); // Route thực tế
        $response->assertStatus(200)
            ->assertJsonCount(15, 'data'); // Kiểm tra trả về 15 bài viết
    }

    /**
     * Test lấy bài viết theo ID.
     *
     * @test
     */
    public function it_can_get_a_single_post()
    {
        $post = Post::factory()->create(); // Tạo bài viết giả lập

        $response = $this->getJson("/api/get/{$post->id}"); // Route thực tế
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
                'description' => $post->description,
            ]);
    }

    /**
     * Test tạo bài viết.
     *
     * @test
     */
    public function it_can_create_a_post()
    {
        $user = User::factory()->create(); // Giả lập người dùng
        $data = [
            'user_id' => $user->id,
            'description' => 'New Post Content',
        ];

        $response = $this->postJson('/api/insert', $data); // Route thực tế
        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('posts', $data);
    }

    /**
     * Test cập nhật bài viết.
     *
     * @test
     */
    public function it_can_update_a_post()
    {
        $post = Post::factory()->create(); // Tạo bài viết giả lập
        $updateData = [
            'description' => 'Updated Content',
        ];

        $response = $this->putJson("/api/update/{$post->id}", $updateData); // Route thực tế
        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('posts', $updateData); // Kiểm tra bài viết đã được cập nhật
    }

    /**
     * Test xoá bài viết.
     *
     * @test
     */
    public function it_can_delete_a_post()
    {
        $user = User::factory()->create(); // Giả lập người dùng
        $this->actingAs($user); // Giả lập người dùng đăng nhập

        $post = Post::factory()->create(['user_id' => $user->id]); // Tạo bài viết thuộc về user

        $response = $this->deleteJson("/api/delete/{$post->id}"); // Route thực tế
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Delete post successful']);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]); // Kiểm tra bài viết đã bị xoá khỏi cơ sở dữ liệu
    }
}
