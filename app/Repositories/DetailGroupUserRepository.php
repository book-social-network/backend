<?php

namespace App\Repositories;

use App\Models\DetailGroupUser;
use App\Models\Group;
use App\Models\User;
use App\Repositories\Interfaces\DetailGroupUserInterface;

class DetailGroupUserRepository implements DetailGroupUserInterface{
    public function getAllUserInGroup($idGroup){
        $group=Group::find($idGroup);
        return $group->user();
    }
    public function getAllGroupOfUser($idUser){
        $user=User::find($idUser);
        return $user->group();
    }
    public function insertDetailGroupUser($data){
        DetailGroupUser::create($data);
    }
    public function deleteDetailGroupUser($id){
        $DetailGroupUser=DetailGroupUser::find($id);
        if(!empty($DetailGroupUser)){
            $DetailGroupUser->delete();
        }
    }
}
