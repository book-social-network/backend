<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPostBook extends Model
{
    use HasFactory;
    protected $table='detail_post_books';
    protected $fillable=[
        'id',
        'post_id',
        'book_id',
    ];
    // one
    public function post(){
        return $this->belongsTo(Post::class);
    }
    public function book(){
        return $this->belongsTo(Book::class);
    }
}
