<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='authors';
    protected $fillable=[
        'id',
        'name',
        'born',
        'died',
        'description',
        'image',
    ];
}
