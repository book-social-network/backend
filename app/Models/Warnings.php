<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warnings extends Model
{
    use HasFactory;
    protected $table='warnings';
    protected $fillable=[
        'id',
        'description',
        'post_id',
        'user_id',
    ];
    // one
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function post(){
        return $this->belongsTo(Post::class);
    }
}
