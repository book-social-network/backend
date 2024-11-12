<?php

namespace App\Repositories;

use App\Models\Like;
use App\Repositories\Interfaces\LikeInterface;

class LikeRepository implements LikeInterface{
    public function getAllLikeOfPost($idPost){
        return Like::where('post_id', $idPost)->get();

    }
    public function getAllLikeOfUser($idUser){
        return Like::where('user_id', $idUser)->get();
    }
    public function getStateOfPost($idPost, $isUser){
        $like=Like::where('post_id', $idPost)->where('user_id', $isUser)->first();
        return !empty($like);
    }
    public function getLike($id){
        $like=Like::find($id);
        return !empty($like) ? $like : null;
    }
    public function insertLike($data){
        return Like::create($data);
    }
    public function deleteLike($id){
        $Like=Like::find($id);
        if(!empty($Like)){
            $Like->delete();
        }
    }

}
