<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentControllerTest extends TestCase
{
    use RefreshDatabase;

    // Test getting an assessment by its ID
    public function test_get_assessment_by_id()
    {
        $assessment = Assessment::factory()->create();
        $response = $this->getJson("/api/get/{$assessment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'assessment' => $assessment->toArray(),
                'book' => $assessment->book->toArray(),
                'user' => $assessment->user->id,
                'authors' => []
            ]);
    }

    // Test inserting a new assessment
    public function test_insert_assessment()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $data = [
            'description' => 'Great book!',
            'star' => 5,
            'book_id' => $book->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/insert', $data);

        $response->assertStatus(201)
            ->assertJson([
                'description' => 'Great book!',
                'star' => 5,
                'book_id' => $book->id,
                'user_id' => $user->id,
            ]);
    }

    // Test updating the state of the book read status
    public function test_update_state_read()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        // Assume that an assessment exists for this user and book
        $assessment = Assessment::factory()->create([
            'book_id' => $book->id,
            'user_id' => $user->id,
        ]);

        $data = ['state_read' => 1]; // Example state

        $response = $this->actingAs($user)->putJson("/api/update-state-read/{$book->id}", $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Update state successful']);
    }

    // Test updating an existing assessment
    public function test_update_assessment()
    {
        $assessment = Assessment::factory()->create();
        $data = ['star' => 4];

        $response = $this->putJson("/api/update/{$assessment->id}", $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Update assessment successful']);
    }

    // Test deleting an assessment
    public function test_delete_assessment()
    {
        $assessment = Assessment::factory()->create();

        $response = $this->deleteJson("/api/delete/{$assessment->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Delete assessment successful']);
    }

    // Test getting all assessments of a user
    public function test_get_assessments_by_user()
    {
        $user = User::factory()->create();
        $assessment = Assessment::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/get-assessment-user/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([
                'assessment' => $assessment->toArray(),
            ]);
    }

    // Test getting all assessments of a book
    public function test_get_assessments_by_book()
    {
        $book = Book::factory()->create();
        $assessment = Assessment::factory()->create(['book_id' => $book->id]);

        $response = $this->getJson("/api/get-assessment-book/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([
                'assessment' => $assessment->toArray(),
            ]);
    }
}
