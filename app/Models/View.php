<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $table='views';
    protected $fillable=[
        'id',
        'ip_address',
        'last_visited_at'
    ];
}
