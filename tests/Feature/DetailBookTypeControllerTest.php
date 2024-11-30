<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Type;
use App\Models\DetailBookType;
use App\Repositories\DetailBookTypeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetailBookTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $bookRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = new DetailBookTypeRepository();
    }

    /** @test */
    public function it_can_get_detail_book_type()
    {
        // Tạo sách và loại sách với Factory
        $detailBookType = DetailBookType::factory()->create();

        // Kiểm tra phương thức
        $detail = $this->bookRepository->getDetailBookType($detailBookType->id);

        // Xác minh
        $this->assertNotNull($detail);
        $this->assertEquals($detail->id, $detailBookType->id);
    }

    /** @test */
    public function it_can_get_all_types_of_book()
    {
        // Tạo sách và loại sách với Factory
        $book = Book::factory()->create();
        $type = Type::factory()->create();

        // Gắn loại sách vào sách
        $book->type()->attach($type);

        // Kiểm tra phương thức
        $types = $this->bookRepository->getAllTypeOfBook($book->id);

        // Xác minh
        $this->assertCount(1, $types);
        $this->assertTrue($types->contains($type));
    }

    /** @test */
    public function it_can_get_all_books_of_type()
    {
        // Tạo sách và loại sách với Factory
        $type = Type::factory()->create();
        $book = Book::factory()->create();

        // Gắn sách vào loại
        $type->book()->attach($book);

        // Kiểm tra phương thức
        $books = $this->bookRepository->getAllBookOfType($type->id);

        // Xác minh
        $this->assertCount(1, $books);
        $this->assertTrue($books->contains($book));
    }

    /** @test */
    public function it_can_insert_detail_book_type()
    {
        // Tạo dữ liệu mẫu
        $book = Book::factory()->create();
        $type = Type::factory()->create();

        // Dữ liệu cho chi tiết loại sách
        $data = [
            'book_id' => $book->id,
            'type_id' => $type->id,
        ];

        // Kiểm tra phương thức
        $detailBookType = $this->bookRepository->insertDetailBookType($data);

        // Xác minh
        $this->assertDatabaseHas('detail_book_types', $data);
        $this->assertNotNull($detailBookType);
    }

    /** @test */
    public function it_can_delete_detail_book_type()
    {
        // Tạo DetailBookType với Factory
        $detailBookType = DetailBookType::factory()->create();

        // Kiểm tra trước khi xóa
        $this->assertDatabaseHas('detail_book_types', ['id' => $detailBookType->id]);

        // Kiểm tra phương thức
        $this->bookRepository->deleteDetailBookType($detailBookType->id);

        // Xác minh sau khi xóa
        $this->assertDatabaseMissing('detail_book_types', ['id' => $detailBookType->id]);
    }
}
