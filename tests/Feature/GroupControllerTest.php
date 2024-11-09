<?php

namespace Tests\Feature;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase; // Đảm bảo rằng cơ sở dữ liệu được làm mới trước mỗi test

    /**
     * Test lấy tất cả nhóm.
     *
     * @return void
     */
    public function testGetAllGroups()
    {
        // Tạo một số nhóm mẫu
        Group::factory()->count(3)->create();

        // Gửi yêu cầu GET đến route /group/get-all
        $response = $this->get('/api/group/get-all');

        // Kiểm tra mã trạng thái HTTP trả về là 200 (OK)
        $response->assertStatus(200);

        // Kiểm tra rằng các nhóm đã được trả về trong phản hồi
        $response->assertJsonCount(3);
    }

    /**
     * Test thêm nhóm mới.
     *
     * @return void
     */
    public function testInsertGroup()
    {
        // Dữ liệu nhóm mới
        $data = [
            'name' => 'New Group',
            'description' => 'Description of the new group',
        ];

        // Gửi yêu cầu POST đến route /group/insert
        $response = $this->post('/api/group/insert', $data);

        // Kiểm tra mã trạng thái HTTP trả về là 201 (Created)
        $response->assertStatus(201);

        // Kiểm tra rằng nhóm mới đã được thêm vào cơ sở dữ liệu
        $this->assertDatabaseHas('groups', [
            'name' => 'New Group',
        ]);
    }

    /**
     * Test cập nhật nhóm.
     *
     * @return void
     */
    public function testUpdateGroup()
    {
        // Tạo một nhóm mẫu
        $group = Group::factory()->create();

        // Dữ liệu cập nhật cho nhóm
        $data = [
            'name' => 'Updated Group',
            'description' => 'Updated description',
        ];

        // Gửi yêu cầu POST đến route /group/update/{id}
        $response = $this->post("/api/group/update/{$group->id}", $data);

        // Kiểm tra mã trạng thái HTTP trả về là 200 (OK)
        $response->assertStatus(200);

        // Kiểm tra rằng nhóm đã được cập nhật trong cơ sở dữ liệu
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Updated Group',
        ]);
    }

    /**
     * Test xóa nhóm.
     *
     * @return void
     */
    public function testDeleteGroup()
    {
        // Tạo một nhóm mẫu
        $group = Group::factory()->create();

        // Gửi yêu cầu DELETE đến route /group/delete/{id}
        $response = $this->delete("/api/group/delete/{$group->id}");

        // Kiểm tra mã trạng thái HTTP trả về là 204 (No Content)
        $response->assertStatus(204);

        // Kiểm tra rằng nhóm đã bị xóa khỏi cơ sở dữ liệu
        $this->assertDatabaseMissing('groups', [
            'id' => $group->id,
        ]);
    }

    /**
     * Test lấy tất cả bài viết trong nhóm.
     *
     * @return void
     */
    public function testGetAllPostInGroup()
    {
        // Tạo một nhóm và một số bài viết liên quan
        $group = Group::factory()->create();
        $group->posts()->createMany([
            ['title' => 'Post 1', 'content' => 'Content for post 1'],
            ['title' => 'Post 2', 'content' => 'Content for post 2'],
        ]);

        // Gửi yêu cầu GET đến route /group/get-all-post-group/{id}
        $response = $this->get("/api/group/get-all-post-group/{$group->id}");

        // Kiểm tra mã trạng thái HTTP trả về là 200 (OK)
        $response->assertStatus(200);

        // Kiểm tra rằng phản hồi có chứa các bài viết của nhóm
        $response->assertJsonCount(2);
    }
}

