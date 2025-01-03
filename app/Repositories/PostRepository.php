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
    public function getAllPostNew(){
        $sevenDaysAgo = now()->subDays(7);
        return Post::where('created_at', '>=', $sevenDaysAgo)->orderBy('created_at', 'desc')->get();
    }
    public function getAllPostOnPage($page , $num){
        return Post::orderBy('created_at', 'desc')->skip(($page - 1) * $num)->take($num)->get();
    }
    public function getPost($id){
        return Post::find($id);
    }
    public function getAllPostReport(){
        return Post::withCount('warnings')
        ->having('warnings_count', '>=', 1)
        ->orderBy('warnings_count', 'desc')
        ->get();
    }
    public function getAllPostInGroup($id, $page=null, $num=null){
        if($page==null && $num==null){
            return Post::whereHas('detail_group_user', function ($query) use ($id) {
                $query->where('group_id', $id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        }
        return Post::whereHas('detail_group_user', function ($query) use ($id) {
            $query->where('group_id', $id);
        })
        ->orderBy('created_at', 'desc')
        ->skip(($page - 1) * $num)->take($num)
        ->get();
    }

    public function getAllPostByUser($id){
        return Post::where('user_id', $id)->where('detail_group_user_id',null)->orderBy('created_at', 'desc')->get();
    }
    public function getAllPostByUserNotInGroup($id){
        return Post::where('user_id', $id)->where('detail_group_user_id', null)->orderBy('created_at', 'desc')->get();
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
