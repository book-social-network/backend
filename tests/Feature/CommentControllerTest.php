<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $commentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commentRepository = app()->make(\App\Repositories\CommentRepository::class);
    }

    /** @test */
    public function it_can_get_a_comment_by_id()
    {
        $comment = Comment::factory()->create();

        $foundComment = $this->commentRepository->getComment($comment->id);

        $this->assertNotNull($foundComment);
        $this->assertEquals($comment->id, $foundComment->id);
    }

    /** @test */
    public function it_can_get_all_comments_by_user()
    {
        $user = User::factory()->create();
        Comment::factory()->count(3)->create(['user_id' => $user->id]);

        $comments = $this->commentRepository->getAllCommentByUser($user->id);

        $this->assertCount(3, $comments);
        $this->assertEquals($user->id, $comments->first()->user_id);
    }

    /** @test */
    public function it_can_get_all_comments_on_post()
    {
        $post = Post::factory()->create();
        Comment::factory()->count(4)->create(['post_id' => $post->id]);

        $comments = $this->commentRepository->getAllCommentOnPost($post->id);

        $this->assertCount(4, $comments);
        $this->assertEquals($post->id, $comments->first()->post_id);
    }

    /** @test */
    public function it_can_insert_a_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $data = [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'description' => 'This is a comment',
        ];

        $comment = $this->commentRepository->insertComment($data);

        $this->assertDatabaseHas('comments', $data);
        $this->assertEquals('This is a comment', $comment->description);
    }

    /** @test */
    public function it_can_update_a_comment()
    {
        $comment = Comment::factory()->create([
            'description' => 'Old comment content',
        ]);
        $data = [
            'description' => 'Updated comment content',
        ];

        $updatedComment = $this->commentRepository->updateComment($data, $comment->id);

        $this->assertDatabaseHas('comments', $data);
        $this->assertEquals('Updated comment content', $updatedComment->description);
    }

    /** @test */
    public function it_can_delete_a_comment()
    {
        $comment = Comment::factory()->create();

        $this->commentRepository->deleteComment($comment->id);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
