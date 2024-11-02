<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface
{
    public function getAllUsers()
    {
        return User::get();
    }
    public function getUser($id)
    {
        return User::find($id);
    }
    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
    public function insertUser($data)
    {
        return User::create($data);
    }
    public function updateUser($data, $id)
    {
        $user=User::find($id);
        $user->update($data);
    }
    public function deleteUser($id)
    {
        $user = User::find($id);
        if(!empty($user)){
            $user->delete();
        }
    }
    public function getByName($name){
        return User::where('name', 'like', '%' . $name . '%')->get();
    }
}
