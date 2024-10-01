<?php

namespace App\Repositories;

use App\Models\Group;
use App\Repositories\Interfaces\GroupInterface;

class GroupRepository implements GroupInterface{
    public function getGroup($id){
        return Group::find($id);
    }
    public function insertGroup($data){
        Group::create($data);
    }
    public function updateGroup($data, $id){
        $Group=Group::find($id);
        $Group->update($data);
    }
    public function deleteGroup($id){
        $Group=Group::find($id);
        if(!empty($Group)){
            $Group->delete();
        }
    }

}
