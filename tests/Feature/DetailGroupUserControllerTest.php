<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Group;
use App\Models\User;
use App\Models\DetailGroupUser;
use App\Repositories\DetailGroupUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetailGroupUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $groupRepository;

    // Khởi tạo repository
    public function setUp(): void
    {
        parent::setUp();
        $this->groupRepository = new DetailGroupUserRepository();
    }

    /** @test */
    public function it_can_get_all_users_in_group()
    {
        // Giả lập dữ liệu
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $group->user()->attach($user);

        // Kiểm tra phương thức
        $users = $this->groupRepository->getAllUserInGroup($group->id);

        // Xác minh
        $this->assertCount(1, $users);
        $this->assertTrue($users->contains($user));
    }

    /** @test */
    public function it_can_get_all_groups_of_user()
    {
        // Giả lập dữ liệu
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $user->group()->attach($group);

        // Kiểm tra phương thức
        $groups = $this->groupRepository->getAllGroupOfUser($user->id);

        // Xác minh
        $this->assertCount(1, $groups);
        $this->assertTrue($groups->contains($group));
    }

    /** @test */
    public function it_can_get_detail_of_group_and_user()
    {
        // Giả lập dữ liệu
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $detailGroupUser = DetailGroupUser::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'role' => 'member',
            'state' => 1
        ]);

        // Kiểm tra phương thức
        $detail = $this->groupRepository->getDetail($group->id, $user->id);

        // Xác minh
        $this->assertNotNull($detail);
        $this->assertEquals($detail->group_id, $group->id);
        $this->assertEquals($detail->user_id, $user->id);
    }

    /** @test */
    public function it_can_check_user_in_group()
    {
        // Giả lập dữ liệu
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $detailGroupUser = DetailGroupUser::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'role' => 'member',
            'state' => 1
        ]);

        // Kiểm tra phương thức
        $result = $this->groupRepository->checkUserInGroup($detailGroupUser->id, $user->id);

        // Xác minh
        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_update_detail_group_user()
    {
        // Giả lập dữ liệu
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $detailGroupUser = DetailGroupUser::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'role' => 'member',
            'state' => 1
        ]);

        // Cập nhật dữ liệu
        $data = ['role' => 'admin'];
        $this->groupRepository->updateDetailGroupUser($data, $detailGroupUser->id);

        // Kiểm tra lại
        $detailGroupUser->refresh();
        $this->assertEquals('admin', $detailGroupUser->role);
    }

    /** @test */
    public function it_can_delete_detail_group_user()
    {
        // Giả lập dữ liệu
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $detailGroupUser = DetailGroupUser::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'role' => 'member',
            'state' => 1
        ]);

        // Xóa dữ liệu
        $this->groupRepository->deleteDetailGroupUser($detailGroupUser->id);

        // Kiểm tra
        $this->assertNull(DetailGroupUser::find($detailGroupUser->id));
    }
}

