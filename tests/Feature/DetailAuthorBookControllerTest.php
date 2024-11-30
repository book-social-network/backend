<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Author;
use App\Models\Book;
use App\Models\DetailAuthorBook;
use App\Repositories\DetailAuthorBookRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetailAuthorBookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $authorBookRepository;

    // Khởi tạo repository trước mỗi test
    public function setUp(): void
    {
        parent::setUp();
        $this->authorBookRepository = new DetailAuthorBookRepository();
    }
    /** @test */
    public function it_can_get_detail_of_author_book()
    {
        // Arrange: tạo chi tiết tác giả sách mẫu
        $detailAuthorBook = DetailAuthorBook::factory()->create();

        // Act: gọi phương thức getDetailAuthorBook
        $detail = $this->authorBookRepository->getDetailAuthorBook($detailAuthorBook->id);

        // Assert: kiểm tra chi tiết trả về đúng
        $this->assertEquals($detailAuthorBook->id, $detail->id);
    }

    /** @test */
    public function it_can_insert_detail_author_book()
    {
        // Arrange: dữ liệu cần chèn vào
        $data = [
            'author_id' => Author::factory()->create()->id,
            'book_id' => Book::factory()->create()->id,
        ];

        // Act: gọi phương thức insertDetailAuthorBook
        $detailAuthorBook = $this->authorBookRepository->insertDetailAuthorBook($data);

        // Assert: kiểm tra chi tiết tác giả sách đã được tạo thành công
        $this->assertDatabaseHas('detail_author_books', $data);
    }

    /** @test */
    public function it_can_delete_detail_author_book()
    {
        // Arrange: tạo chi tiết tác giả sách mẫu
        $detailAuthorBook = DetailAuthorBook::factory()->create();

        // Act: gọi phương thức deleteDetailAuthorBook
        $this->authorBookRepository->deleteDetailAuthorBook($detailAuthorBook->id);

        // Assert: kiểm tra chi tiết tác giả sách đã bị xóa
        $this->assertDatabaseMissing('detail_author_books', ['id' => $detailAuthorBook->id]);
    }
}
