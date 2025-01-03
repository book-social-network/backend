<?php

namespace App\Repositories;

use App\Models\DetailGroupUser;
use App\Models\Group;
use App\Models\User;
use App\Repositories\Interfaces\DetailGroupUserInterface;

class DetailGroupUserRepository implements DetailGroupUserInterface{
    public function getAllUserInGroup($idGroup){
        $group=Group::find($idGroup);
        $details=$group->detail_group_users()->get();
        $data=[];
        foreach ($details as $detail){
            if($detail->state==1){
                $data[] = $detail->user()->first();
            }
        }
        return $data;
    }
    public function getAllGroupOfUser($idUser){
        $user=User::find($idUser);
        return $user->group()->get();
    }
    public function getDetail($group, $user){
        return DetailGroupUser::where('group_id',$group)->where('user_id',$user)->first();
    }
    public function getAdminGroup($group){
        return DetailGroupUser::where('group_id', $group)->where('role','admin')->get();
    }
    public function getAllUserWantToJoin($idGroup){
        $users=DetailGroupUser::where('group_id',$idGroup)->where('state', 0);
        return $users;
    }
    public function getAllDetailGroupUser(){
        return DetailGroupUser::get();
    }
    public function getDetailGroupUser($id){
        return DetailGroupUser::find($id);
    }
    public function updateDetailGroupUser($data,$id){
        $DetailGroupUser=DetailGroupUser::find($id);
        $DetailGroupUser->update($data);
    }
    public function insertDetailGroupUser($data){
        return DetailGroupUser::create($data);
    }
    public function deleteDetailGroupUser($id){
        $DetailGroupUser=DetailGroupUser::find($id);
        if(!empty($DetailGroupUser)){
            $DetailGroupUser->delete();
        }
    }
    public function checkUserInGroup($id, $idUser){
        if($id!=null){
            $group= DetailGroupUser::find($id)->group()->first();
            if($group!=null){
                if($group->state==true && $group->detail_group_users()->where('user_id',$idUser!=null));
                {
                    return true;
                }
            }
        }
        return false;
    }
}
