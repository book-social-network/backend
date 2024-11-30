<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo người dùng giả để kiểm tra quyền truy cập
        $this->user = User::factory()->create();

        // Giả lập việc sử dụng BookRepository
        $this->bookRepository = app()->make('App\Repositories\BookRepository');
    }

    /** @test */
    public function it_can_get_all_books()
    {
        // Tạo một số sách giả lập
        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();

        // Lấy tất cả sách thông qua repository
        $books = $this->bookRepository->getAllBooks();

        // Kiểm tra rằng các sách đã được lấy ra đúng
        $this->assertCount(2, $books);
        $this->assertTrue($books->contains('id', $book1->id));
        $this->assertTrue($books->contains('id', $book2->id));
    }

    /** @test */
    public function it_can_get_book_by_id()
    {
        // Tạo một sách giả lập
        $book = Book::factory()->create();

        // Lấy sách thông qua repository theo ID
        $foundBook = $this->bookRepository->getBook($book->id);

        // Kiểm tra xem sách có tồn tại và đúng ID
        $this->assertNotNull($foundBook);
        $this->assertEquals($book->id, $foundBook->id);
    }

    /** @test */
    public function it_can_get_books_by_name()
    {
        // Tạo một số sách giả lập
        $book1 = Book::factory()->create(['name' => 'Test Book']);
        $book2 = Book::factory()->create(['name' => 'Another Book']);

        // Tìm sách theo tên thông qua repository
        $books = $this->bookRepository->getByName('Test');

        // Kiểm tra rằng sách có tên chứa 'Test' được tìm thấy
        $this->assertCount(1, $books);
        $this->assertEquals('Test Book', $books->first()->name);
    }
    /** @test */
    public function it_can_update_book()
    {
        // Tạo sách mẫu
        $book = Book::factory()->create();

        // Dữ liệu cập nhật
        $data = ['name' => 'Updated Book Title'];

        // Cập nhật sách thông qua repository
        $this->bookRepository->updateBook($data, $book->id);

        // Kiểm tra xem sách đã được cập nhật trong cơ sở dữ liệu
        $this->assertDatabaseHas('books', ['id' => $book->id, 'name' => 'Updated Book Title']);
    }

    /** @test */
    public function it_can_delete_book()
    {
        // Tạo sách mẫu
        $book = Book::factory()->create();

        // Xóa sách thông qua repository
        $this->bookRepository->deleteBook($book->id);

        // Kiểm tra xem sách đã bị xóa khỏi cơ sở dữ liệu
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
