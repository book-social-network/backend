<?php

namespace Tests\Feature;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Database\Factories\PostFactory;

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
        $data = [
            'name' => 'Test Group',
            'description' => 'This is a test group',
            'image' => UploadedFile::fake()->image('group.jpg'),
        ];

        $response = $this->post('/api/group/insert', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('groups', ['name' => 'Test Group']);
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
        $group = Group::factory()->create();

        $response = $this->delete("/api/group/delete/{$group->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }

    /**
     * Test lấy tất cả bài viết trong nhóm.
     *
     * @return void
     */
    public function testGetAllPostInGroup()
    {
        $group = Group::factory()->create();
        PostFactory::factory()->create(['group_id' => $group->id]);

        $response = $this->get("/api/group/get-all-post-group/{$group->id}");
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'posts');
    }
}
