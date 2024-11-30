<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Group;
use App\Repositories\GroupRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $groupRepository;

    // Thiết lập cho mỗi bài test
    protected function setUp(): void
    {
        parent::setUp();
        $this->groupRepository = new GroupRepository();
    }

    /** @test */
    public function it_can_get_group_by_id()
    {
        // Arrange: tạo một nhóm mẫu
        $group = Group::factory()->create(); // Sử dụng Factory để tạo dữ liệu mẫu

        // Act: gọi phương thức getGroup
        $fetchedGroup = $this->groupRepository->getGroup($group->id);

        // Assert: kiểm tra nếu dữ liệu trả về đúng như mong đợi
        $this->assertNotNull($fetchedGroup);
        $this->assertEquals($group->id, $fetchedGroup->id);
    }

    /** @test */
    public function it_can_get_all_groups()
    {
        // Arrange: tạo một số nhóm mẫu
        Group::factory()->count(3)->create();

        // Act: gọi phương thức getAllGroup
        $groups = $this->groupRepository->getAllGroup();

        // Assert: kiểm tra nếu có ít nhất 3 nhóm được trả về
        $this->assertCount(3, $groups);
    }

    /** @test */
    public function it_can_insert_group()
    {
        // Arrange: dữ liệu mẫu để tạo nhóm
        $data = [
            'name' => 'Test Group',
            'title' => 'Description of Test Group'
        ];

        // Act: gọi phương thức insertGroup
        $group = $this->groupRepository->insertGroup($data);

        // Assert: kiểm tra nhóm mới đã được thêm vào cơ sở dữ liệu
        $this->assertDatabaseHas('groups', $data);
    }
    /** @test */
    public function it_can_delete_group()
    {
        // Arrange: tạo một nhóm mẫu
        $group = Group::factory()->create();

        // Act: gọi phương thức deleteGroup
        $this->groupRepository->deleteGroup($group->id);

        // Assert: kiểm tra nhóm đã bị xóa khỏi cơ sở dữ liệu
        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}
