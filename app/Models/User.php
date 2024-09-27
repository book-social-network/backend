<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject, AuthenticatableContract
{
    use HasFactory, Notifiable, Authenticatable;

    protected $connection = 'mongodb'; // Kết nối MongoDB
    protected $collection = 'users'; // Tên collection
    protected $primaryKey = '_id'; // Đảm bảo sử dụng '_id' cho MongoDB
    public $incrementing = false; // Đặt thành false vì khóa chính không tự động tăng

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Implement JWTSubject methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getAuthIdentifierName()
    {
        return '_id'; // Chỉ định rằng khóa chính là '_id'
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
