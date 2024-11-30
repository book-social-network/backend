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
            'author_id' => Author::factory(), 
            'book_id' => Book::factory(),    
        ];
    }
}
