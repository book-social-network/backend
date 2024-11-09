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
        // Arrange: Chuẩn bị dữ liệu
        $authorData = [
            'name' => 'John Doe',
            'born' => '1980-01-01',
            'dob' => '1980-01-01',
            'description' => 'Author description',
            'image' => \Illuminate\Http\Testing\File::image('john_doe.jpg', 200, 300), // Tạo tệp ảnh giả để kiểm tra
        ];

        // Act: Gọi API để tạo tác giả
        $response = $this->postJson('/api/author/insert', $authorData);

        // Assert: Kiểm tra mã trạng thái HTTP là 201 (Created)
        $response->assertStatus(201);

        // Lấy dữ liệu từ response để kiểm tra URL ảnh trả về
        $responseData = $response->json();

        // Assert: Kiểm tra dữ liệu có tồn tại trong cơ sở dữ liệu
        $this->assertDatabaseHas('authors', [
            'name' => 'John Doe',
            'born' => '1980-01-01',
            'dob' => '1980-01-01',
            'description' => 'Author description',
            'image' => $responseData['image'],  // Kiểm tra URL ảnh trả về từ Cloudinary
        ]);
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
