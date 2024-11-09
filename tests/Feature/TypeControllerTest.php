<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Type;

class TypeControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_get_all_types()
    {
        // Arrange: Create some types
        Type::factory()->count(3)->create();

        // Act: Call the API endpoint
        $response = $this->get('/api/type/get-all');

        // Assert: Check the response is successful and contains the types
        $response->assertStatus(200);
        $response->assertJsonCount(3);  // Ensure it returns 3 items
    }

    public function test_insert_type()
    {
        // Arrange: Chuẩn bị dữ liệu
        $data = ['name' => 'New Type'];

        // Act: Gọi API để thêm mới một loại
        $response = $this->post('/api/type/insert', $data);

        // Kiểm tra mã trạng thái trả về là 200
        $response->assertStatus(200);  // Thay từ assertStatus(201) thành assertStatus(200)

        // Kiểm tra dữ liệu có tồn tại trong cơ sở dữ liệu
        $this->assertDatabaseHas('types', $data);
    }


    public function test_update_type()
    {
        // Arrange: Create a type
        $type = Type::factory()->create();
        $data = ['name' => 'Updated Type'];

        // Act: Call the API to update the type
        $response = $this->post("/api/type/update/{$type->id}", $data);

        // Assert: Check the type was updated
        $response->assertStatus(200);
        $this->assertDatabaseHas('types', $data);  // Ensure the updated data is in the DB
    }

    public function test_delete_type()
    {
        // Arrange: Create a type
        $type = Type::factory()->create();

        $response = $this->delete("/api/type/delete/{$type->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('types', ['id' => $type->id]);  // Ensure it's removed
    }
}
