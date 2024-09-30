<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='notifications';
    protected $fillable=[
        'id',
        'from_id',
        'user_id',
        'information',
        'type'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
