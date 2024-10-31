<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\FollowInterface;
use App\Repositories\Interfaces\NotificationInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    private $user,$follow, $notification;
    public function __construct(UserInterface $userInterface,FollowInterface $followInterface, NotificationInterface $notificationInterface){
        $this->follow=$followInterface;
        $this->notification=$notificationInterface;
        $this->user=$userInterface;
    }
    public function getAllFollow(){
        $user=auth()->user();
        if(!$user){
            return response()->json(['message' => 'Please login'], 404);
        }
        $followers=$this->follow->getAllFollowOfUser($user->id);
        return response()->json($followers);
    }
    public function handleFollow($id){
        $user=auth()->user();
        $follower=$this->user->getUser($id);
        if(!$user || !$follower){
            return response()->json(['message' => 'Please login before following user'], 404);
        }
        // dd($follower->id);
        $follow=$this->follow->insertFollow([
            'user_id' => $user->id,
            'follower' => $follower->id
        ]);
        // handle Realtime notification
        // follower
        $this->notification->insertNotification([
            'from_id' => $user->id,
            'to_id' => $follower->id,
            'information' => $follower->name.' vừa gửi theo dõi bạn',
            'from_type' => 'user',
        ]);
        return response()->json(['message' => 'Follow successful'], 404);
    }
    public function handleUnfollow($id){
        $user=auth()->user();
        $follow=$this->follow->getFollow($id);
        if(!$user || !$follow){
            return response()->json(['message' => 'Please login before following user'], 404);
        }
        $this->follow->deleteFollow($id);
        return response()->json(['message' => 'Unfollow successful'], 404);
    }
    public function suggestFriends(){
        $user=auth()->user();
        if(!$user){
            return response()->json(['message' => 'Please login before following user'], 404);
        }
        $friend=$this->follow->suggestFriends($user->id);
        return response()->json($friend);
    }

}
