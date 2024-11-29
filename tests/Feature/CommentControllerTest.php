<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_comment()
    {
        $comment = Comment::factory()->create(); // Tạo comment mẫu

        $response = $this->getJson('/api/get/' . $comment->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $comment->id,
                'description' => $comment->content,
            ]);
    }

    public function test_get_all_comments_by_user()
    {
        $user = User::factory()->create(); // Tạo user mẫu
        $comments = Comment::factory()->count(5)->create(['user_id' => $user->id]); // Tạo 5 comment cho user

        $response = $this->getJson('/api/get-all-comment/' . $user->id);

        $response->assertStatus(200)
            ->assertJsonCount(5)
            ->assertJsonFragment(['user_id' => $user->id]);
    }

    public function test_get_all_comments_on_post()
    {
        $post = Post::factory()->create(); // Tạo post mẫu
        $comments = Comment::factory()->count(3)->create(['post_id' => $post->id]); // Tạo 3 comment cho post

        $response = $this->getJson('/api/comments/post/' . $post->id);

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonFragment(['post_id' => $post->id]);
    }

    public function test_insert_comment()
    {
        $user = User::factory()->create(); // Tạo user mẫu
        $post = Post::factory()->create(); // Tạo post mẫu

        $data = [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'description' => 'This is a comment',
        ];

        $response = $this->postJson('/api/insert-comment', $data);

        $response->assertStatus(201)
            ->assertJson([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'description' => 'This is a comment',
            ]);
    }

    public function test_update_comment()
    {
        $comment = Comment::factory()->create(); // Tạo comment mẫu
        $data = ['decription' => 'Updated comment content'];

        $response = $this->putJson('/api/update-comment/' . $comment->id, $data);

        $response->assertStatus(200)
            ->assertJson([
                'description' => 'Updated comment content',
            ]);
    }

    public function test_delete_comment()
    {
        $comment = Comment::factory()->create(); // Tạo comment mẫu

        $response = $this->deleteJson('/api/delete-comment/' . $comment->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
