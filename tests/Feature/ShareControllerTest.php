<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Share;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShareControllerTest extends TestCase
{
    use RefreshDatabase;  // Đảm bảo dữ liệu được reset sau mỗi test

    protected $shareRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shareRepository = new \App\Repositories\ShareRepository();
    }

    /** @test */
    public function it_can_get_all_shares()
    {
        // Tạo một vài bản ghi share
        $share = Share::factory()->create();

        // Lấy tất cả shares
        $shares = $this->shareRepository->getAllShare();

        // Kiểm tra xem danh sách shares có chứa share vừa tạo không
        $this->assertCount(1, $shares);
        $this->assertEquals($share->id, $shares->first()->id);
    }

    /** @test */
    public function it_can_get_a_share_by_id()
    {
        $share = Share::factory()->create();

        // Tìm kiếm share theo ID
        $fetchedShare = $this->shareRepository->getShare($share->id);

        $this->assertEquals($share->id, $fetchedShare->id);
    }

    /** @test */
    public function it_can_get_all_shares_of_a_book()
    {
        $book = Book::factory()->create();
        $share = Share::factory()->create(['book_id' => $book->id]);

        // Lấy tất cả shares của một book
        $shares = $this->shareRepository->getAllShareOfBook($book->id);

        // Kiểm tra xem share của book có được lấy đúng không
        $this->assertCount(1, $shares);
        $this->assertEquals($book->id, $shares->first()->book_id);
    }

    /** @test */
    public function it_can_get_all_shares_of_a_user()
    {
        $user = User::factory()->create();
        $share = Share::factory()->create(['user_id' => $user->id]);

        // Lấy tất cả shares của một user
        $shares = $this->shareRepository->getAllShareOfUser($user->id);

        // Kiểm tra xem share của user có được lấy đúng không
        $this->assertCount(1, $shares);
        $this->assertEquals($user->id, $shares->first()->user_id);
    }

    /** @test */
    public function it_can_insert_a_share()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $data = [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'link_share' => 'https://example.com/share', // Thêm giá trị cho link_share
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Kiểm tra số lượng share trước khi insert
        $this->assertCount(0, Share::all());

        // Thực hiện insert
        $this->shareRepository->insertShare($data);

        // Kiểm tra số lượng share sau khi insert
        $this->assertCount(1, Share::all());
    }


    /** @test */
    public function it_can_delete_a_share()
    {
        $share = Share::factory()->create();

        // Kiểm tra số lượng share trước khi delete
        $this->assertCount(1, Share::all());

        // Thực hiện delete
        $this->shareRepository->deleteShare($share->id);

        // Kiểm tra số lượng share sau khi delete
        $this->assertCount(0, Share::all());
    }
}
