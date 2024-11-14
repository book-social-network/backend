<?php

namespace App\Repositories;

use App\Models\Follow;
use App\Models\User;
use App\Repositories\Interfaces\FollowInterface;

class FollowRepository implements FollowInterface{
    public function getAllFollowOfUser($idUser){
        return Follow::where('user_id', $idUser)->get();
    }
    public function getFollow($idFollower, $idUser){
        return Follow::where('follower', $idFollower)->where('user_id', $idUser)->first();
    }
    public function insertFollow($data){
        return Follow::create($data);
    }
    public function deleteFollow($id){
        $Follow=Follow::find($id);
        if(!empty($Follow)){
            $Follow->delete();
        }
    }
    public function suggestFriends($userId)
    {
        $user = User::findOrFail($userId);

        $friends = $user->follows()->pluck('follower');

        if ($friends->isEmpty()) {
            $suggestedFriends = User::where('id', '!=', $userId)
                ->inRandomOrder() // Lấy ngẫu nhiên
                ->take(20) // Lấy 20 người
                ->get();

            return $suggestedFriends;
        }

        $suggestedFriends = User::where('id', '!=', $userId)
            ->whereNotIn('id', $friends)
            ->withCount(['follows as common_friends_count' => function ($query) use ($friends) {
                $query->whereIn('follower', $friends);
            }])
            ->orderByDesc('common_friends_count')
            ->take(20)
            ->get();

        return $suggestedFriends;
    }
}
