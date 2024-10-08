<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='authors';
    protected $fillable=[
        'id',
        'name',
        'born',
        'dob',
        'died',
        'description',
        'image',
    ];
    public function book()
    {
        return $this->belongsToMany(Book::class, 'detail_author_books', 'author_id', 'book_id');
    }
    public function type()
    {
        return $this->belongsToMany(Type::class, 'detail_author_types', 'type_id', 'author_id');
    }
}
