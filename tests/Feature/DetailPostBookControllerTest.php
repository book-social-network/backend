<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Post;
use App\Models\DetailPostBook;
use App\Repositories\DetailPostBookRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetailPostBookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $postBookRepository;

    // Khởi tạo repository trước mỗi test
    public function setUp(): void
    {
        parent::setUp();
        $this->postBookRepository = new DetailPostBookRepository();
    }

   

    /** @test */
    public function it_can_get_detail_of_post_book()
    {
        // Arrange: tạo chi tiết bài đăng sách mẫu
        $detailPostBook = DetailPostBook::factory()->create();

        // Act: gọi phương thức getDetailPostBook
        $detail = $this->postBookRepository->getDetailPostBook($detailPostBook->post_id, $detailPostBook->book_id);

        // Assert: kiểm tra chi tiết trả về đúng
        $this->assertEquals($detailPostBook->id, $detail->id);
    }

    /** @test */
    public function it_can_insert_detail_post_book()
    {
        // Arrange: dữ liệu cần chèn vào
        $data = [
            'book_id' => Book::factory()->create()->id,
            'post_id' => Post::factory()->create()->id,
        ];

        // Act: gọi phương thức insertDetailPostBook
        $detailPostBook = $this->postBookRepository->insertDetailPostBook($data);

        // Assert: kiểm tra chi tiết bài đăng sách đã được tạo thành công
        $this->assertDatabaseHas('detail_post_books', $data);
    }

    /** @test */
    public function it_can_delete_detail_post_book()
    {
        // Arrange: tạo chi tiết bài đăng sách mẫu
        $detailPostBook = DetailPostBook::factory()->create();

        // Act: gọi phương thức deleteDetailPostBook
        $this->postBookRepository->deleteDetailPostBook($detailPostBook->id);

        // Assert: kiểm tra chi tiết bài đăng sách đã bị xóa
        $this->assertDatabaseMissing('detail_post_books', ['id' => $detailPostBook->id]);
    }
}
