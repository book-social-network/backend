<?php

namespace Tests\Feature;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_a_single_group()
    {
        $group = Group::factory()->create();

        $response = $this->getJson("/api/get/{$group->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $group->id,
                'name' => $group->name,
            ]);
    }

    /** @test */
    public function it_can_get_all_groups()
    {
        Group::factory()->count(10)->create();

        $response = $this->getJson('/api/get-all');

        $response->assertStatus(200)
            ->assertJsonCount(10); // Đảm bảo có 10 nhóm trong kết quả trả về
    }

    /** @test */
    public function it_can_insert_a_group()
    {
        $data = [
            'name' => 'New Group',
            'title' => 'Group Title',
            'description' => 'This is a new group.',
        ];

        $response = $this->postJson('/api/insert', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('groups', $data);
    }

    /** @test */
    public function it_can_update_a_group()
    {
        $group = Group::factory()->create();

        $updateData = [
            'name' => 'Updated Group Name',
            'title' => 'Updated Title',
            'description' => 'Updated description.',
        ];

        $response = $this->putJson("/api/update/{$group->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('groups', $updateData);
    }

    /** @test */
    public function it_can_delete_a_group()
    {
        $group = Group::factory()->create();

        $response = $this->deleteJson("/api/delete/{$group->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Delete group successful']);

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}
