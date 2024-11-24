<?php

namespace Tests\Feature;

use App\Models\Author;
use Database\Factories\TypeFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Type;
use App\Models\DetailAuthorType;

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
    public function testInsertAuthor()
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
        // Tạo một tác giả mới
        $author = Author::factory()->create();

        // Gửi yêu cầu DELETE đến API xóa tác giả
        $response = $this->deleteJson("/api/author/delete/{$author->id}");

        // Kiểm tra phản hồi trả về mã trạng thái HTTP 204 (No Content)
        $response->assertStatus(200);

        // Kiểm tra tác giả đã bị xóa khỏi cơ sở dữ liệu
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
    //Type
    /** @test */
    public function it_can_insert_type_for_author()
    {
        // Tạo dữ liệu giả cho Author và Type
        $author = Author::factory()->create();
        $type = Type::factory()->create();

        $data = [
            'type_id' => $type->id,
            'author_id' => $author->id,
        ];

        // Gửi yêu cầu POST đến API insert-type
        $response = $this->postJson('/api/insert-type', $data);

        // Kiểm tra kết quả trả về
        $response->assertStatus(200)
            ->assertJson(['message' => 'Insert type for author successful']);

        // Kiểm tra trong cơ sở dữ liệu
        $this->assertDatabaseHas('detail_author_types', [
            'author_id' => $author->id,
            'type_id' => $type->id
        ]);
    }

    /** @test */
    public function it_can_delete_type_for_author()
    {
        // Tạo dữ liệu giả cho Author, Type và DetailAuthorType
        $author = Author::factory()->create();
        $type = Type::factory()->create();
        $detail = DetailAuthorType::create([
            'author_id' => $author->id,
            'type_id' => $type->id
        ]);

        // Gửi yêu cầu DELETE đến API delete-type
        $response = $this->deleteJson("/api/delete-type/{$detail->id}");

        // Kiểm tra kết quả trả về
        $response->assertStatus(200)
            ->assertJson(['message' => 'Delete type for author successful']);

        // Kiểm tra cơ sở dữ liệu không còn dữ liệu này
        $this->assertDatabaseMissing('detail_author_types', [
            'id' => $detail->id
        ]);
    }

    /** @test */
    public function it_can_get_all_types_of_author()
    {
        // Tạo dữ liệu giả cho Author và Type
        $author = Author::factory()->create();
        $type = Type::factory()->create();
        DetailAuthorType::create([
            'author_id' => $author->id,
            'type_id' => $type->id
        ]);

        // Gửi yêu cầu GET đến API get-all-type
        $response = $this->getJson("/api/get-all-type/{$author->id}");

        // Kiểm tra kết quả trả về
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['author_id', 'type_id']
            ]);
    }
}
