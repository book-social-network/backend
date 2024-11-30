<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Author;
use App\Models\Type;
use App\Models\DetailAuthorType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetailAuthorTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $detailAuthorTypeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detailAuthorTypeRepository = app()->make(\App\Repositories\DetailAuthorTypeRepository::class);
    }

    /** @test */
    public function it_can_get_detail_author_type_by_id()
    {
        $detailAuthorType = DetailAuthorType::factory()->create();

        $retrieved = $this->detailAuthorTypeRepository->getDetailAuthorType($detailAuthorType->id);

        $this->assertEquals($detailAuthorType->id, $retrieved->id);
    }

    /** @test */
    public function it_can_get_all_types_for_an_author()
    {
        $author = Author::factory()->create();
        $types = Type::factory(3)->create();

        foreach ($types as $type) {
            DetailAuthorType::factory()->create([
                'author_id' => $author->id,
                'type_id' => $type->id,
            ]);
        }

        $retrievedTypes = $this->detailAuthorTypeRepository->getAllTypeWithAuthor($author->id);

        $this->assertCount(3, $retrievedTypes);
        $this->assertTrue($retrievedTypes->pluck('id')->contains($types->first()->id));
    }

    /** @test */
    public function it_can_get_all_authors_for_a_type()
    {
        $type = Type::factory()->create();
        $authors = Author::factory(3)->create();

        foreach ($authors as $author) {
            DetailAuthorType::factory()->create([
                'author_id' => $author->id,
                'type_id' => $type->id,
            ]);
        }

        $retrievedAuthors = $this->detailAuthorTypeRepository->getAllAuthorWithType($type->id);

        $this->assertCount(3, $retrievedAuthors);
        $this->assertTrue($retrievedAuthors->pluck('id')->contains($authors->first()->id));
    }

    /** @test */
    public function it_can_insert_a_new_detail_author_type()
    {
        $author = Author::factory()->create();
        $type = Type::factory()->create();
        $data = [
            'author_id' => $author->id,
            'type_id' => $type->id,
        ];

        $detailAuthorType = $this->detailAuthorTypeRepository->insertDetailAuthorType($data);

        $this->assertDatabaseHas('detail_author_types', $data);
        $this->assertEquals($author->id, $detailAuthorType->author_id);
        $this->assertEquals($type->id, $detailAuthorType->type_id);
    }

    /** @test */
    public function it_can_delete_a_detail_author_type()
    {
        $detailAuthorType = DetailAuthorType::factory()->create();

        $this->detailAuthorTypeRepository->deleteDetailAuthorType($detailAuthorType->id);

        $this->assertDatabaseMissing('detail_author_types', ['id' => $detailAuthorType->id]);
    }
}
