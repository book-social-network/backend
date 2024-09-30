<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='posts';
    protected $fillable=[
        'id',
        'name',
        'image_group',
        'state',

    ];
}
