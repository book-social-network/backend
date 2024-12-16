<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table='posts';
    protected $fillable=[
        'id',
        'detail_group_user_id',
        'user_id',
        'description',
        'warning',
    ];
    // many to many
    public function user_on_comments(){
        return $this->belongsToMany(User::class,'comments','post_id','user_id');
    }
    public function user_on_likes(){
        return $this->belongsToMany(User::class, 'likes','post_id', 'user_id');
    }
    public function book(){
        return $this->belongsToMany(Book::class,'detail_post_books','post_id','book_id');
    }
    // many
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
    public function like(){
        return $this->hasMany(Like::class);
    }

    public function detail_post_book(){
        return $this->hasMany(DetailPostBook::class);
    }
    // one
    public function detail_group_user(){
        return $this->belongsTo(DetailGroupUser::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
