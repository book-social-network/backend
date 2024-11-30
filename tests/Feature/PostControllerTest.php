<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $postRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->postRepository = new PostRepository(); // Tạo instance của PostRepository
    }

    /** @test */
    public function it_can_get_all_posts()
    {
        $user = User::factory()->create();
        Post::factory()->create(['user_id' => $user->id]);
        Post::factory()->create(['user_id' => $user->id]);

        $posts = $this->postRepository->getAllPost();

        $this->assertCount(2, $posts); // Kiểm tra có 2 bài viết
    }

    /** @test */
    public function it_can_get_all_posts_on_page()
    {
        $user = User::factory()->create();
        Post::factory()->create(['user_id' => $user->id]);
        Post::factory()->create(['user_id' => $user->id]);
        Post::factory()->create(['user_id' => $user->id]);

        $page = 1;
        $num = 2;

        $posts = $this->postRepository->getAllPostOnPage($page, $num);

        $this->assertCount(2, $posts); // Kiểm tra có 2 bài viết trên trang 1
    }

    /** @test */
    public function it_can_get_single_post()
    {
        $post = Post::factory()->create();

        $result = $this->postRepository->getPost($post->id);

        $this->assertNotNull($result); // Kiểm tra bài viết đã tồn tại
    }

    /** @test */
    public function it_can_get_all_posts_by_user()
    {
        $user = User::factory()->create();
        Post::factory()->create(['user_id' => $user->id, 'detail_group_user_id' => null]);
        Post::factory()->create(['user_id' => $user->id, 'detail_group_user_id' => null]);

        $posts = $this->postRepository->getAllPostByUser($user->id);

        $this->assertCount(2, $posts); // Kiểm tra có 2 bài viết của user
    }

    /** @test */
    public function it_can_insert_post()
    {
        $user = \App\Models\User::factory()->create(); // Tạo bản ghi trong bảng users
        $post = Post::factory()->create([
            'user_id' => $user->id, // Gán ID của user
            'description' => 'Test content',
        ]);

        // Tiếp tục các kiểm tra
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'description' => 'Test content',
        ]);
    }

    /** @test */
    public function it_can_update_post()
    {
        $post = Post::factory()->create();
        $data = ['description' => 'Updated content'];

        $this->postRepository->updatePost($data, $post->id);

        $this->assertDatabaseHas('posts', $data); // Kiểm tra bài viết đã được cập nhật
    }

    /** @test */
    public function it_can_delete_post()
    {
        $post = Post::factory()->create();

        $this->postRepository->deletePost($post->id);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]); // Kiểm tra bài viết đã bị xóa
    }
}
