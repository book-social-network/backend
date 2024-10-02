<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DetailGroupUserInterface;
use App\Repositories\Interfaces\GroupInterface;
use App\Repositories\Interfaces\NotificationInterface;
use Illuminate\Http\Request;

class DetailGroupUserController extends Controller
{
    private $group;
    private $detailGroupUser;
    private $notification;
    public function __construct(GroupInterface $groupInterface,DetailGroupUserInterface $detailGroupUserInterface, NotificationInterface $notificationInterface){
        $this->detailGroupUser=$detailGroupUserInterface;
        $this->notification=$notificationInterface;
        $this->group=$groupInterface;
    }
    public function index(){
        $details=$this->detailGroupUser->getAllDetailGroupUser();
        return response()->json($details);
    }
    public function getAllUserInGroup($idGroup){
        $group=$this->group->getGroup($idGroup);
        if(!$group){
        return response()->json(['message'=> 'Not found group'],404);
        }
        $users=$this->detailGroupUser->getAllUserInGroup($group->id);
        return response()->json($users);
    }
    public function insert(Request $request){
        $request->validate([
            'group_id' => 'required',
        ]);
        $group=$this->group->getGroup($request->get('group_id'));
        if(!$group){
            return response()->json(['message' => 'Not found group to join'], 404);
        }
        $user = auth()->user();
        $data=[
            'group_id' => $group->id,
            'user_id' => $user->id,
            'state' => 0,
            'role' => 'member'
        ];
        $detail=$this->detailGroupUser->insertDetailGroupUser($data);


        //  Handle Realtime Notifications
        // send notification for member
        $this->notification->insertNotification([
            'from_id' => $group->id,
            'to_id' => $user->id,
            'information' => 'Bạn vừa gửi yêu cầu tham gia nhóm '. $group->name,
            'from_type' => 'group',
        ]);

        // send notification for group
        $this->notification->insertNotification([
            'from_id' => $user->id,
            'to_id' => $group->id,
            'information' => $user->name.' vừa gửi yêu cầu tham gia nhóm ',
            'to_type' => 'group',
        ]);

        return response()->json($detail);
    }

    public function updateState(Request $request){
        $request->validate([
            'id'=> 'required'
        ]);
        $detail=$this->detailGroupUser->getDetailGroupUser($request->get('id'));
        if(!$detail){
            return response()->json(['message' => 'Not found user or group'], 404);
        }
        $data=[
            'group_id' => $detail->group_id,
            'user_id' => $detail->user_id,
            'state' => 1
        ];
        $this->detailGroupUser->updateDetailGroupUser($data, $detail->id);
        return response()->json(['message'=> 'User join group successful']);
    }
    public function updateRole(Request $request){
        $request->validate([
            'id'=> 'required',
            'role' => 'required'
        ]);
        $detail=$this->detailGroupUser->getDetailGroupUser($request->get('id'));
        if(!$detail){
            return response()->json(['message' => 'Not found user or group'], 404);
        }
        $data=[
            'group_id' => $detail->group_id,
            'user_id' => $detail->user_id,
            'role' => $request->get('role')
        ];
        $this->detailGroupUser->updateDetailGroupUser($data, $detail->id);
        return response()->json(['message'=> 'Update role for user successful']);
    }
    public function delete($id){
        $detail=$this->detailGroupUser->getDetailGroupUser($id);
        if(!$detail){
            return response()->json(['message' => 'Not found user or group to delete'], 404);
        }
        $this->detailGroupUser->deleteDetailGroupUser($detail->id);
        return response()->json(['message'=> 'Delete user from group successful']);
    }
}
