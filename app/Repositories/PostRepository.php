<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostInterface;

class PostRepository implements PostInterface{
    public function getAllPost(){
        return Post::get();
    }
    public function getPost($id){
        return Post::find($id);
    }
    public function getAllPostInGroup($id){
        return Post::where('group_id', $id)->get();
    }
    public function getAllPostByUser($id){
        return Post::where('user_id', $id)->get();
    }
    public function insertPost($data){
        Post::create($data);
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

}
