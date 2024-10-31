<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    /** @test */
    public function it_can_get_all_users()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/user/get-all');

        $response->assertStatus(200);
    }
    /** @test */
    public function it_can_get_a_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/user/get/' . $user->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $user->id]);
    }
    /** @test */
    public function it_returns_404_when_user_not_found()
    {
        $response = $this->getJson('/api/user/get/999');

        $response->assertStatus(404)
                ->assertJson(['message' => 'Not found user']);
    }
    /** @test */
    public function it_can_insert_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/user/insert', $data);

        $response->assertStatus(200)
                ->assertJsonFragment(['name' => 'John Doe']);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }
    /** @test */
    public function it_can_update_user()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Jane Doe',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];

        $response = $this->postJson('/api/user/update/' . $user->id, $data);

        $response->assertStatus(200)
                ->assertJson(['message' => 'Update user successful']);
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }

    /** @test */
    public function it_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson('/api/user/delete/' . $user->id);

        $response->assertStatus(200)
                ->assertJson(['message' => 'Delete user successful']);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_get_all_posts_of_user()
    {
        $user = User::factory()->create();
        $user->posts()->createMany([
            ['description' => 'Post 1', 'user_id' => $user->id],
            ['description' => 'Post 2', 'user_id' => $user->id],
        ]);

        $response = $this->getJson('/api/user/get-all-post/' . $user->id);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_get_all_comments_of_user()
    {
        // Tạo một user
        $user = User::factory()->create();

        // Tạo một bài post cho user đó
        $post = $user->posts()->create([
            'description' => 'This is a test post',
            'user_id' => $user->id,
        ]);

        // Tạo nhiều comment cho bài post
        $post->comments()->createMany([
            ['post_id'=>$post->id, 'user_id' => $user->id,'description' => 'Comment 1'],
            ['post_id'=>$post->id, 'user_id' => $user->id,'description' => 'Comment 2'],
            ['post_id'=>$post->id, 'user_id' => $user->id,'description' => 'Comment 3'],
        ]);

        // Gửi yêu cầu để lấy tất cả comment của user
        $response = $this->getJson('/api/user/get-all-comment/' . $user->id);

        // Kiểm tra phản hồi
        $response->assertStatus(200); // Kiểm tra có 3 comment
    }

    /** @test */
    public function it_can_get_all_likes_of_user()
    {
        $user = User::factory()->create();
        $user->likes()->createMany([
            ['user_id' => $user->id,'post_id' => 1],
            ['user_id' => $user->id,'post_id' => 2],
        ]);

        $response = $this->getJson('/api/user/get-all-like/' . $user->id);

        $response->assertStatus(200)
                ->assertJsonCount(2);
    }
}
