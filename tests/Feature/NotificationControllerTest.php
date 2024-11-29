<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $notifications;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo một user
        $this->user = User::factory()->create();

        // Tạo dữ liệu mẫu thông báo
        $this->notifications = Notification::factory()
            ->count(15)
            ->sequence(
                fn($sequence) => [
                    'to_id' => $this->user->id,
                    'to_type' => 'member',
                    'from_id' => $sequence->index + 1,
                    'from_type' => 'post',
                ]
            )
            ->create();
    }

    /** @test */
    public function it_can_get_all_notifications_of_a_user()
    {
        $repository = app()->make(\App\Repositories\NotificationRepository::class);

        $notifications = $repository->getAllNotificationOfUser($this->user->id, 1, 5);

        $this->assertCount(5, $notifications);
        $this->assertEquals($this->user->id, $notifications->first()->to_id);
    }

    /** @test */
    public function it_can_get_a_single_notification()
    {
        $repository = app()->make(\App\Repositories\NotificationRepository::class);
        $notification = $this->notifications->first();

        $retrieved = $repository->getNotification($notification->id);

        $this->assertNotNull($retrieved);
        $this->assertEquals($notification->id, $retrieved->id);
    }

    /** @test */
    public function it_can_insert_a_notification()
    {
        $repository = app()->make(\App\Repositories\NotificationRepository::class);

        $data = [
            'to_id' => $this->user->id,
            'to_type' => 'member',
            'from_id' => 9,
            'from_type' => 'post',
            'information' => 'Test notification',
            'state' => 0,
        ];

        $notification = $repository->insertNotification($data);

        $this->assertDatabaseHas('notifications', ['information' => 'Test notification']);
        $this->assertEquals($data['to_id'], $notification->to_id);
    }

    /** @test */
    public function it_can_update_a_notification()
    {
        $repository = app()->make(\App\Repositories\NotificationRepository::class);
        $notification = $this->notifications->first();

        $repository->updateNotification(['state' => 1], $notification->id);

        $this->assertDatabaseHas('notifications', ['id' => $notification->id, 'state' => 1]);
    }

    /** @test */
    public function it_can_delete_a_notification()
    {
        $repository = app()->make(\App\Repositories\NotificationRepository::class);
        $notification = $this->notifications->first();

        $repository->deleteNotification($notification->id);

        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    /** @test */
    public function it_can_get_the_quantity_of_pages_for_notifications()
    {
        $repository = app()->make(\App\Repositories\NotificationRepository::class);

        $quantityPages = $repository->getQuantityPageNotificationOfUser($this->user->id, 5);

        $this->assertEquals(3, $quantityPages);
    }
}
