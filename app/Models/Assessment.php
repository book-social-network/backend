<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='assessments';
    protected $fillable=[
        'id',
        'description',
        'star',
        'state_read',
        'book_id',
        'user_id',
    ];
    // one
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function book(){
        return $this->belongsTo(Book::class);
    }
}
