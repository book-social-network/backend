<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $table='groups';
    protected $fillable=[
        'id',
        'name',
        'title',
        'description',
        'image_group',
        'state',
    ];
    // many to many
    public function user() {
        return $this->belongsToMany(User::class, 'detail_group_users', 'group_id', 'user_id');
    }
    public function detail_group_users(){
        return $this->hasMany(DetailGroupUser::class);
    }
}
