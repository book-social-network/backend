<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='shares';
    protected $fillable=[
        'id',
        'link_share',
        'book_id',
        'user_id',
    ];
}
