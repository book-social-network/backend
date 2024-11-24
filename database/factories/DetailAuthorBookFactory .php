<?php
// database/factories/DetailAuthorBookFactory.php

namespace Database\Factories;

use App\Models\DetailAuthorBook;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailAuthorBookFactory extends Factory
{
    protected $model = DetailAuthorBook::class;

    public function definition()
    {
        return [
            'author_id' => Author::factory(), // Tự động tạo một Author và lấy ID
            'book_id' => Book::factory(),     // Tự động tạo một Book và lấy ID
        ];
    }
}
