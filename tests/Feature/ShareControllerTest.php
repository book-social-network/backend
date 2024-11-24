<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Share;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShareControllerTest extends TestCase
{
    use RefreshDatabase; // Đảm bảo database luôn ở trạng thái ban đầu cho mỗi test

    /**
     * Test lấy tất cả các share.
     *
     * @return void
     */
    public function test_get_all_shares()
    {
        // Tạo dữ liệu mẫu
        Share::factory()->count(5)->create();

        // Gọi API getAllShare
        $response = $this->getJson('/api/share/get-all');

        // Kiểm tra kết quả trả về
        $response->assertStatus(200)
            ->assertJsonCount(5); // Kiểm tra số lượng share trả về là 5
    }

    /**
     * Test lấy một share cụ thể theo ID.
     *
     * @return void
     */
    public function test_get_specific_share()
    {
        // Tạo một share
        $share = Share::factory()->create();

        // Gọi API getShare
        $response = $this->getJson("/api/share/get/{$share->id}");

        // Kiểm tra kết quả trả về
        $response->assertStatus(200)
            ->assertJson([
                'book_id' => $share->book_id,
                'user_id' => $share->user_id,
                'link_share' => $share->link_share, // Kiểm tra link_share thay vì content
            ]);
    }

    /**
     * Test thêm mới share.
     *
     * @return void
     */
    public function test_insert_share()
    {
        // Dữ liệu mới
        $data = [
            'book_id' => 1,
            'user_id' => 1,
            'link_share' => 'https://example.com/share/1', // Sửa thành URL hợp lệ
        ];

        // Gọi API insertShare
        $response = $this->postJson('/api/share/insert', $data);

        // Kiểm tra kết quả
        $response->assertStatus(200);
        $this->assertDatabaseHas('share', $data); // Kiểm tra dữ liệu có trong cơ sở dữ liệu
    }

    /**
     * Test xóa share.
     *
     * @return void
     */
    public function test_delete_share()
    {
        // Tạo một share
        $share = Share::factory()->create();

        // Gọi API deleteShare
        $response = $this->deleteJson("/api/share/delete/{$share->id}");

        // Kiểm tra kết quả
        $response->assertStatus(200);
        $this->assertDatabaseMissing('share', ['id' => $share->id]); // Kiểm tra dữ liệu đã bị xóa khỏi cơ sở dữ liệu
    }
}
