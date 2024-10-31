<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table='notifications';
    protected $fillable=[
        'id',
        'from_id',
        'to_id',
        'information',
        'from_type',
        'to_type',
        'state'
    ];
}
