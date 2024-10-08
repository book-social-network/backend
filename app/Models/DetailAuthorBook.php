<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAuthorBook extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='detail_author_books';
    protected $fillable=[
        'id',
        'book_id',
        'author_id',
    ];
    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function author(){
        return $this->belongsTo(Author::class);
    }
}
