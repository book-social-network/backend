<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='follows';
    protected $fillable=[
        'id',
        'from_id',
        'follower'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
