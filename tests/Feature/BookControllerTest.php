<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllBooks()
    {
        // Tạo 5 bản ghi Book giả để kiểm tra
        Book::factory()->count(5)->create();

        // Gửi yêu cầu GET tới API để lấy tất cả sách
        $response = $this->getJson('/api/book/get-all');

        // Kiểm tra xem status code là 200 và số lượng sách trả về là 5
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function testGetBook()
    {
        // Tạo một bản ghi Book giả
        $book = Book::factory()->create();

        // Gửi yêu cầu GET để lấy thông tin sách theo ID
        $response = $this->getJson('/api/book/get/' . $book->id);

        // Kiểm tra status code và dữ liệu trả về có chứa các thông tin mong muốn
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $book->id,
            'name' => $book->name,
        ]);
    }

    public function testInsertBook()
    {
        // Dữ liệu sách để thêm mới
        $data = [
            'name' => 'New Book',
            'author' => 'Author Name',
            'ratings' => 10,
            'reviews' => 5,
            'assessment_score' => 4.5,
            'image' => 'https://i.pinimg.com/736x/5d/9a/69/5d9a69ed36d92006cf4a02dfded9952f.jpg',
            'link_book' => 'https://example.com',
            'description' => 'Description of the new book.',
        ];

        // Gửi yêu cầu POST để thêm sách
        $response = $this->postJson('/api/book/insert', $data);

        // Kiểm tra status code và xác nhận dữ liệu đã được lưu vào database
        $response->assertStatus(201);
        $this->assertDatabaseHas('books', $data);
    }

    public function testUpdateBook()
    {
        // Tạo một bản ghi Book giả
        $book = Book::factory()->create();

        // Dữ liệu cập nhật sách
        $data = [
            'name' => 'Updated Book',
        ];

        // Gửi yêu cầu POST để cập nhật sách
        $response = $this->postJson('/api/book/update/' . $book->id, $data);

        // Kiểm tra status code và xác nhận dữ liệu đã được cập nhật trong database
        $response->assertStatus(200);
        $this->assertDatabaseHas('books', $data);
    }

    public function testDeleteBook()
    {
        // Tạo một bản ghi Book giả
        $book = Book::factory()->create();

        // Gửi yêu cầu DELETE để xóa sách
        $response = $this->deleteJson('/api/book/delete/' . $book->id);

        // Kiểm tra status code và xác nhận sách đã bị xóa khỏi database
        $response->assertStatus(200);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}