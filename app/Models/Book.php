<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';
    protected $fillable = [
        'id',
        'name',
        'image',
        'ratings',
        'reviews',
        'assessment_score',
        'link_book',
        'description'
    ];
    // many to many
    public function author()
    {
        return $this->belongsToMany(Author::class, 'detail_author_books', 'book_id', 'author_id');
    }
    public function post()
    {
        return $this->belongsToMany(Post::class, 'detail_post_books', 'book_id', 'post_id');
    }
    public function type()
    {
        return $this->belongsToMany(Type::class, 'detail_book_types', 'book_id', 'type_id');
    }
    // many
    public function assessments(){
        return $this->hasMany(Assessment::class);
    }
    public function shares(){
        return $this->hasMany(Share::class);
    }
    public function detail_post_books(){
        return $this->hasMany(DetailPostBook::class);
    }
    public function detail_book_types(){
        return $this->hasMany(DetailBookType::class);
    }
    public function detail_author_books(){
        return $this->hasMany(DetailAuthorBook::class);
    }
}
