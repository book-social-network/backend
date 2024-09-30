<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBookType extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='detail_book_types';
    protected $fillable=[
        'id',
        'book_id',
        'type_id',
    ];
    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function type(){
        return $this->belongsTo(type::class);
    }
}
