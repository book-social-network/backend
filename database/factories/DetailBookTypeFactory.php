<?php

namespace Database\Factories;

use App\Models\DetailBookType;
use App\Models\Book;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailBookTypeFactory extends Factory
{
    protected $model = DetailBookType::class;

    public function definition()
    {
        return [
            'book_id' => Book::factory(),  // Sử dụng Factory của Book
            'type_id' => Type::factory(),  // Sử dụng Factory của Type
        ];
    }
}
