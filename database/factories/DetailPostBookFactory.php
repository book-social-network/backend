<?php

namespace Database\Factories;

use App\Models\DetailPostBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailPostBookFactory extends Factory
{
    protected $model = DetailPostBook::class;

    public function definition()
    {
        return [
            'book_id' => \App\Models\Book::factory(),
            'post_id' => \App\Models\Post::factory(),
        ];
    }
}
