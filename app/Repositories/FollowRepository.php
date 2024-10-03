<?php

namespace App\Repositories;

use App\Models\Follow;
use App\Repositories\Interfaces\FollowInterface;

class FollowRepository implements FollowInterface{
    public function getAllFollowOfUser($idUser){
        return Follow::where('book_id', $idUser)->get();
    }
    public function getFollow($id){
        return Follow::find($id);
    }
    public function insertFollow($data){
        Follow::create($data);
    }
    public function deleteFollow($id){
        $Follow=Follow::find($id);
        if(!empty($Follow)){
            $Follow->delete();
        }
    }
}
