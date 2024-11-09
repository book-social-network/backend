<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase; // Đảm bảo cơ sở dữ liệu được làm mới cho mỗi test

    /**
     * Test tạo tác giả mới.
     *
     * @return void
     */
    public function testCreateAuthor()
    {
        $authorData = [
            'name' => 'John Doe',
            'born' => '1980-01-01',
            'dob' => '1980-01-01',
            'died' => null,
            'description' => 'Author description',
            'image' => \Illuminate\Http\Testing\File::image('john_doe.jpg', 200, 300),
        ];

        $response = $this->postJson('/api/author/insert', $authorData);

        $response->assertStatus(201); // Kiểm tra mã trạng thái HTTP 201 (Created)
        $this->assertDatabaseHas('authors', $authorData); // Kiểm tra xem dữ liệu có được lưu vào DB
    }

    /**
     * Test lấy tất cả tác giả.
     *
     * @return void
     */
    public function testGetAllAuthors()
    {
        $author = Author::factory()->create(); // Sử dụng factory để tạo tác giả giả

        $response = $this->getJson('/api/author/get-all');

        $response->assertStatus(200); // Kiểm tra mã trạng thái HTTP 200 (OK)
        $response->assertJsonFragment(['name' => $author->name]); // Kiểm tra xem tên tác giả có trong kết quả không
    }

    /**
     * Test cập nhật thông tin tác giả.
     *
     * @return void
     */
    public function testUpdateAuthor()
    {
        $author = Author::factory()->create();

        $updatedData = [
            'name' => 'Updated Author Name',
            'born' => '1985-02-02',
            'dob' => '1985-02-02',
        ];

        $response = $this->putJson("/api/author/update/{$author->id}", $updatedData);

        $response->assertStatus(200); // Kiểm tra mã trạng thái HTTP 200 (OK)
        $this->assertDatabaseHas('authors', $updatedData); // Kiểm tra xem dữ liệu đã được cập nhật trong DB
    }

    /**
     * Test xóa tác giả.
     *
     * @return void
     */
    public function testDeleteAuthor()
    {
        $author = Author::factory()->create();

        $response = $this->deleteJson("/api/author/delete/{$author->id}");

        $response->assertStatus(204); // Kiểm tra mã trạng thái HTTP 204 (No Content)
        $this->assertDatabaseMissing('authors', ['id' => $author->id]); // Kiểm tra xem tác giả đã bị xóa khỏi DB
    }
}
