<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(\App\Repositories\LikeRepository::class);
    }

    /** @test */
    public function it_can_get_all_likes_of_a_post()
    {
        $post = Post::factory()->create();
        Like::factory(5)->create(['post_id' => $post->id]);

        $result = $this->repository->getAllLikeOfPost($post->id);

        $this->assertCount(5, $result);
        $this->assertEquals($post->id, $result->first()->post_id);
    }

    /** @test */
    public function it_can_get_all_likes_of_a_user()
    {
        $user = User::factory()->create();
        Like::factory(3)->create(['user_id' => $user->id]);

        $result = $this->repository->getAllLikeOfUser($user->id);

        $this->assertCount(3, $result);
        $this->assertEquals($user->id, $result->first()->user_id);
    }

    /** @test */
    public function it_can_get_like_state_of_a_post_for_a_user()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        Like::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $result = $this->repository->getStateOfPost($post->id, $user->id);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_get_a_specific_like_by_post_and_user()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        $like = Like::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $result = $this->repository->getLike($post->id, $user->id);

        $this->assertNotNull($result);
        $this->assertEquals($like->id, $result->id);
    }

    /** @test */
    public function it_can_insert_a_like()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        $data = [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ];

        $result = $this->repository->insertLike($data);

        $this->assertDatabaseHas('likes', $data);
        $this->assertEquals($data['post_id'], $result->post_id);
        $this->assertEquals($data['user_id'], $result->user_id);
    }

    /** @test */
    public function it_can_delete_a_like()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        Like::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $this->repository->deleteLike($post->id, $user->id);

        $this->assertDatabaseMissing('likes', ['post_id' => $post->id, 'user_id' => $user->id]);
    }
}
