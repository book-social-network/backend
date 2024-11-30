<?php

namespace Database\Factories;

use App\Models\DetailAuthorType;
use App\Models\Author;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailAuthorTypeFactory extends Factory
{
    protected $model = DetailAuthorType::class;

    public function definition()
    {
        return [
            'author_id' => Author::factory(),
            'type_id' => Type::factory(),
        ];
    }
}
