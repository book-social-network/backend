<?php

namespace App\Repositories;

use App\Models\DetailGroupUser;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
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
        return Post::where('detail_group_user_id', $id)->skip(($page - 1) * $num)->take($num)->orderBy('created_at', 'desc')->get();
    }
    public function getAllPostByUser($id){
        return Post::where('user_id', $id)->orderBy('created_at', 'desc')->get();
    }
    public function getAllPostGroupWithUser($idUser) {
        $user = User::find($idUser);

        if (!$user) {
            return null;
        }

        $groups = $user->group()->get();
        $posts = collect();
        foreach ($groups as $group) {
            $details = $group->detail_group_users()->get();

            foreach ($details as $item) {
                $posts = $posts->merge($item->posts()->get());
            }
        }
        $posts = $posts->sortByDesc('created_at');
        return $posts;
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

}
