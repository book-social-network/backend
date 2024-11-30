<?php

namespace Tests\Unit;

use App\Models\Assessment;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $assessmentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assessmentRepository = app()->make(\App\Repositories\AssessmentRepository::class);
    }

    /** @test */
    public function it_can_get_all_assessments()
    {
        Assessment::factory()->count(5)->create();

        $assessments = $this->assessmentRepository->getAllAssessments();

        $this->assertCount(5, $assessments);
    }

    /** @test */
    public function it_can_get_assessment_by_id()
    {
        $assessment = Assessment::factory()->create();

        $foundAssessment = $this->assessmentRepository->getAssessment($assessment->id);

        $this->assertNotNull($foundAssessment);
        $this->assertEquals($assessment->id, $foundAssessment->id);
    }

    /** @test */
    public function it_can_get_assessment_by_book_and_user_id()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $assessment = Assessment::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $foundAssessment = $this->assessmentRepository->getAssessmentWithIdBookAndUser($book->id, $user->id);

        $this->assertNotNull($foundAssessment);
        $this->assertEquals($assessment->id, $foundAssessment->id);
    }

    /** @test */
    public function it_can_get_all_assessments_by_user()
    {
        $user = User::factory()->create();
        Assessment::factory()->count(3)->create(['user_id' => $user->id]);

        $assessments = $this->assessmentRepository->getAllAssessmentByUser($user->id);

        $this->assertCount(3, $assessments);
    }

    /** @test */
    public function it_can_get_all_assessments_by_book()
    {
        $book = Book::factory()->create();
        Assessment::factory()->count(4)->create(['book_id' => $book->id]);

        $assessments = $this->assessmentRepository->getAllAssessmentByBook($book->id);

        $this->assertCount(4, $assessments);
    }

    /** @test */
    public function it_can_insert_an_assessment()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $data = [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'star' => 4,
            'description' => 'Great book!',
        ];

        $assessment = $this->assessmentRepository->insertAssessment($data);

        $this->assertDatabaseHas('assessments', $data);
    }

    /** @test */
    public function it_can_update_an_assessment()
    {
        $assessment = Assessment::factory()->create([
            'star' => 3,
            'description' => 'Good book.',
        ]);
        $data = [
            'star' => 5,
            'description' => 'Excellent book!',
        ];

        $this->assessmentRepository->updateAssessment($data, $assessment->id);

        $this->assertDatabaseHas('assessments', $data);
    }

    /** @test */
    public function it_can_delete_an_assessment()
    {
        $assessment = Assessment::factory()->create();

        $this->assessmentRepository->deleteAssessment($assessment->id);

        $this->assertDatabaseMissing('assessments', ['id' => $assessment->id]);
    }
}
