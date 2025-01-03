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
    public function getLike($idPost,$idUser){
        $like=Like::where('user_id',$idUser)->where('post_id',$idPost)->first();
        return !empty($like) ? $like : null;
    }
    public function insertLike($data){
        return Like::create($data);
    }
    public function deleteLike($idPost, $isUser){
        $Like=Like::where('post_id', $idPost)->where('user_id', $isUser)->first();
        if(!empty($Like)){
            $Like->delete();
        }
    }

}
