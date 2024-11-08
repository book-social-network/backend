<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'ratings' => $this->faker->numberBetween(1, 100),
            'reviews' => $this->faker->numberBetween(1, 50),
            'assessment_score' => $this->faker->randomFloat(2, 0, 5),
            'image' => $this->faker->imageUrl(200, 300, 'books', true, 'Faker'), // URL giả lập cho ảnh sách
            'link_book' => $this->faker->url, // URL giả lập cho liên kết sách
            'description' => substr($this->faker->paragraphs(3, true), 0, 255),
            // Mô tả sách
        ];
    }
}
