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
        return $this->belongsToMany(User::class,'comments','comment_id','post_id');
    }
    public function user_on_likes(){
        return $this->belongsToMany(User::class, 'likes', 'like_id','post_id');
    }
    public function book(){
        return $this->belongsToMany(Book::class,'detail_post_books','book_id','post_id');
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
