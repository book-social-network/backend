<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;
    protected $table='shares';
    protected $fillable=[
        'id',
        'link_share',
        'book_id',
        'user_id',
    ];
    // one
    public function users(){
        return $this->belongsTo(User::class);
    }
    public function books(){
        return $this->belongsTo(Book::class);
    }
}
