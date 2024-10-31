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
    // many to many
    public function user_on_comments(){
        return $this->belongsToMany(User::class,'comments','post_id','comment_id');
    }
    public function user_on_likes(){
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'like_id');
    }
    public function books(){
        return $this->belongsToMany(Book::class,'detail_post_books','post_id','book_id');
    }
    // many
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likes(){
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
