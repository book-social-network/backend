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
    public function getAllUsersNew()
    {
        $sevenDaysAgo = now()->subDays(7);
        return User::where('created_at', '>=', $sevenDaysAgo)->get();
    }
    public function getAllUsersOld()
    {
        $dateThreshold = now()->subDays(90);
        return User::where('lasted_login', '<', $dateThreshold)->get();
    }
    public function getAllPointOfUsers()
    {
        return User::orderBy('point', 'desc')->take(10)->get();
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
