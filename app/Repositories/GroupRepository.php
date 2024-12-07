<?php

namespace App\Repositories;

use App\Models\Group;
use App\Repositories\Interfaces\GroupInterface;

class GroupRepository implements GroupInterface{
    public function getGroup($id){
        return Group::find($id);
    }
    public function getAllGroup(){
        return Group::get();
    }
    public function getAllNewGroup(){
        $sevenDaysAgo = now()->subDays(7);
        return Group::where('created_at', '>=', $sevenDaysAgo)->get();
    }
    public function insertGroup($data){
        return Group::create($data);
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
    public function getByName($name){
        return Group::where('name', 'like', '%' . $name . '%')->get();
    }

}
