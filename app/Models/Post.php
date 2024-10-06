<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='posts';
    protected $fillable=[
        'id',
        'detail_group_user_id',
        'user_id',
        'description',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function detailGroupUser(){
        return $this->belongsTo(DetailGroupUser::class);
    }
    public function book()
    {
        return $this->belongsToMany(Book::class, 'detail_post_books', 'post_id', 'book_id');
    }
}
