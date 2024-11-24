<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_notifications_of_user()
    {
        // Tạo người dùng giả
        $user = User::factory()->create();

        // Tạo một số notification cho người dùng này
        Notification::factory()->count(5)->create([
            'to_id' => $user->id,
            'to_type' => 'member',
        ]);

        // Gọi API get-all để lấy tất cả notifications của người dùng
        $response = $this->getJson("/api/notification/get-all");

        // Kiểm tra kết quả trả về
        $response->assertStatus(200)
            ->assertJsonCount(5); // Kiểm tra số lượng notification trả về là 5
    }

    /** @test */
    public function it_can_update_notification_state()
    {
        $notification = Notification::factory()->create();

        $data = ['state' => 'read'];

        // Gọi API update-state để cập nhật trạng thái
        $response = $this->postJson("/api/notification/update-state/{$notification->id}", $data);

        // Kiểm tra kết quả trả về
        $response->assertStatus(200)
            ->assertJson([
                'state' => 'read',
            ]);

        // Kiểm tra dữ liệu trong cơ sở dữ liệu đã được cập nhật
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'state' => 'read',
        ]);
    }

    /** @test */
    public function it_can_delete_a_notification()
    {
        $notification = Notification::factory()->create();

        // Gọi API delete để xóa notification
        $response = $this->deleteJson("/api/notification/delete/{$notification->id}");

        // Kiểm tra kết quả trả về
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Notification deleted',
            ]);

        // Kiểm tra trong cơ sở dữ liệu đã không còn notification này
        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
        ]);
    }
}
