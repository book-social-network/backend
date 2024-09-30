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
        'photo'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function detailGroupUser(){
        return $this->belongsTo(DetailGroupUser::class);
    }
}
