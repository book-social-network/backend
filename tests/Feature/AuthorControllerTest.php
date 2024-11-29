<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase;  // Đảm bảo dữ liệu được reset sau mỗi test

    protected $authorRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authorRepository = new \App\Repositories\AuthorRepository();
    }

    /** @test */
    public function it_can_get_all_authors()
    {
        Author::factory()->count(5)->create();
        $authors = $this->authorRepository->getAllAuthors();

        $this->assertCount(5, $authors);
    }

    /** @test */
    public function it_can_get_author_by_id()
    {
        $author = Author::factory()->create();
        $fetchedAuthor = $this->authorRepository->getAuthor($author->id);

        $this->assertEquals($author->id, $fetchedAuthor->id);
    }

    /** @test */
    public function it_can_insert_author()
    {
        $data = [
            'name' => 'John Doe',
            'biography' => 'Biography of John Doe'
        ];
        $this->assertCount(0, Author::all());
        $this->authorRepository->insertAuthor($data);
        $this->assertCount(1, Author::all());
    }

    /** @test */
    public function it_can_update_author()
    {
        // Tạo một tác giả trong cơ sở dữ liệu
        $author = Author::factory()->create();

        // Dữ liệu cập nhật
        $data = ['name' => 'Updated Author'];

        // Gọi phương thức updateAuthor
        $this->authorRepository->updateAuthor($data, $author->id);

        // Lấy lại tác giả và kiểm tra dữ liệu
        $author->refresh();
        $this->assertEquals('Updated Author', $author->name);
    }

    /** @test */
    public function it_can_delete_author()
    {
        // Tạo một tác giả trong cơ sở dữ liệu
        $author = Author::factory()->create();

        // Kiểm tra số lượng tác giả trước khi xóa
        $this->assertCount(1, Author::all());

        // Gọi phương thức deleteAuthor
        $this->authorRepository->deleteAuthor($author->id);

        // Kiểm tra số lượng tác giả sau khi xóa
        $this->assertCount(0, Author::all());
    }
}
