<?php

namespace App\Repositories;

use App\Models\DetailGroupUser;
use App\Models\Post;
use App\Repositories\Interfaces\PostInterface;

class PostRepository implements PostInterface{
    public function getAllPost(){
        return Post::orderBy('created_at', 'desc')->get();
    }
    public function getAllPostOnPage($page , $num){
        return Post::orderBy('created_at', 'desc')->skip(($page - 1) * $num)->take($num)->get();
    }
    public function getPost($id){
        return Post::find($id);
    }
    public function getAllPostInGroup($id, $page, $num){
        return Post::where('group_id', $id)->skip(($page - 1) * $num)->take($num)->orderBy('created_at', 'desc')->get();
    }
    public function getAllPostByUser($id){
        return Post::where('user_id', $id)->orderBy('created_at', 'desc')->get();
    }
    public function insertPost($data){
        return Post::create($data);
    }
    public function updatePost($data, $id){
        $post=Post::find($id);
        $post->update($data);
    }

    public function deletePost($id){
        $Post=Post::find($id);
        if(!empty($Post)){
            $Post->delete();
        }
    }
    public function checkUserInGroup($idDetailGroupUser, $idUser){
        if($idDetailGroupUser!=null){
            $group=DetailGroupUser::find($idDetailGroupUser)->group();
            if($group->detail_group_users()->where('user_id',$idUser!=null));
            {
                return true;
            }
        }
        return false;
    }
}
