<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailGroupUser extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='detail_group_users';
    protected $fillable=[
        'id',
        'group_id',
        'user_id',
        'state'
    ];
    public function group(){
        return $this->belongsTo(Group::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
