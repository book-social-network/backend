<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAuthorType extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='detail_author_types';
    protected $fillable=[
        'id',
        'type_id',
        'author_id',
    ];
    // one
    public function type(){
        return $this->belongsTo(Type::class);
    }
    public function author(){
        return $this->belongsTo(Author::class);
    }
}
