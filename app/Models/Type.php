<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='types';
    protected $fillable=[
        'id',
        'name',
    ];
    public function book()
    {
        return $this->belongsToMany(Book::class, 'detail_book_types', 'type_id', 'book_id');
    }
    public function author(){
        return $this->belongsToMany(Author::class, 'detail_auhtor_types', 'type_id', 'author_id');
    }
}
