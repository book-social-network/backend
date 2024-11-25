<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Repositories\Interfaces\DetailGroupUserInterface;
use App\Repositories\Interfaces\GroupInterface;
use App\Repositories\Interfaces\NotificationInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class DetailGroupUserController extends Controller
{
    private $group;
    private $detailGroupUser;
    private $notification,$user;
    public function __construct(GroupInterface $groupInterface, DetailGroupUserInterface $detailGroupUserInterface, NotificationInterface $notificationInterface, UserInterface $userInterface)
    {
        $this->detailGroupUser = $detailGroupUserInterface;
        $this->notification = $notificationInterface;
        $this->group = $groupInterface;
        $this->user=$userInterface;
    }
    public function index()
    {
        $details = $this->detailGroupUser->getAllDetailGroupUser();
        $data = [];
        foreach ($details as $detail) {
            $data[] = [
                'detail' => $detail,
                'group' => $detail->group()->get(),
                'user' => $detail->user()->get()
            ];
        }
        return response()->json($data);
    }
    public function getAllUserWantJoinGroup($idGroup){
        $group = $this->group->getGroup($idGroup);
        if (!$group) {
            return response()->json(['message' => 'Not found group'], 404);
        }
        $user=auth()->user();
        $admins=$this->detailGroupUser->getAdminGroup($idGroup);
        $check=false;
        foreach($admins as $admin){
            if($admin->user_id==$user->id){
                $check=true;
                break;
            }
        }
        if(!$check){
            return response()->json(['message' => 'You are not admin in group'], 404);
        }
        $details=$this->detailGroupUser->getAllUserWantToJoin($idGroup);
        $data=[];
        foreach($details as $detail){
            $data[]=[
                'user' => $detail->user()->first(),
                'detail' => $detail
            ];
        }
        return response()->json($data);
    }
    public function getAllUserInGroup($idGroup)
    {
        $group = $this->group->getGroup($idGroup);
        if (!$group) {
            return response()->json(['message' => 'Not found group'], 404);
        }

        $users = $this->detailGroupUser->getAllUserInGroup($group->id);
        $data = [];

        foreach ($users as $user) {
            $userData = $user->toArray();
            $userData['role_in_group'] = $user->detail_group_users()->first()->role;
            $data[] = $userData;
        }

        return response()->json([
            'group' => $group,
            'users' => $data
        ]);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'group_id' => 'required',
            'user_id' => 'required',
        ]);
        $group = $this->group->getGroup($request->get('group_id'));
        if (!$group) {
            return response()->json(['message' => 'Not found group to join'], 404);
        }
        $user =$this->user->getUser($request->get('user_id'));
        if(!$user){
            return response()->json(['message' => 'Not found user'], 404);
        }
        $data = [
            'group_id' => $group->id,
            'user_id' => $user->id,
            'state' => $group->state == true ? 1 : 0,
            'role' => 'member'
        ];
        $detail = $this->detailGroupUser->insertDetailGroupUser($data);
        //  Handle Realtime Notifications
        $information=$group->state == true ? 'Bạn đã tham gia group ' . $group->name . ' thành công.' : 'Bạn vừa gửi yêu cầu tham gia nhóm ' . $group->name;
        // send notification for member
        $this->notification->insertNotification([
            'from_id' => $group->id,
            'to_id' => $user->id,
            'information' => $information,
            'from_type' => 'group',
        ]);
        broadcast(new NotificationSent($information,$user->id));

        if ($group->state == false) {
            // send notification for group
            $this->notification->insertNotification([
                'from_id' => $user->id,
                'to_id' => $group->id,
                'information' => $user->name . ' vừa gửi yêu cầu tham gia nhóm '.$group->name,
                'to_type' => 'group',
            ]);
            // handle Realtime notification
            broadcast(new NotificationSent($user->name . ' vừa gửi yêu cầu tham gia nhóm '.$group->name,$user->id));
        }

        return response()->json($detail);
    }
    public function updateState(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $detail = $this->detailGroupUser->getDetailGroupUser($request->get('id'));
        if (!$detail) {
            return response()->json(['message' => 'Not found user or group'], 404);
        }
        // notification
        $this->notification->insertNotification([
            'from_id' => $detail->group_id,
            'to_id' => $detail->user_id,
            'information' => 'Bạn đã tham gia group ' . $detail->group()->name . ' thành công.',
            'from_type' => 'group',
        ]);
        // handle Realtime notification
        broadcast(new NotificationSent('Bạn đã tham gia group ' . $detail->group()->name . ' thành công.',$detail->user_id));

        $this->detailGroupUser->updateDetailGroupUser(['state' => 1], $detail->id);
        return response()->json(['message' => 'User join group successful']);
    }
    public function updateRole(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'role' => 'required'
        ]);
        $detail = $this->detailGroupUser->getDetailGroupUser($request->get('id'));
        if (!$detail) {
            return response()->json(['message' => 'Not found user or group'], 404);
        }
        $data = [
            'group_id' => $detail->group_id,
            'user_id' => $detail->user_id,
            'role' => $request->get('role')
        ];
        $group=$detail->group()->first();
        $information=$request->get('role')=='admin'?'Bạn đã được cập nhật quyền quản trị viên trong group '.$group->name:'Bạn đang là thành viên của group '.$group->name;
        $this->notification->insertNotification([
            'from_id' => $detail->group_id,
            'to_id' => $detail->user_id,
            'information' =>$information,
            'from_type' => 'group',
        ]);
        // handle Realtime notification
        broadcast(new NotificationSent($information,$detail->user_id));
        $this->detailGroupUser->updateDetailGroupUser($data, $detail->id);
        return response()->json(['message' => 'Update role for user successful']);
    }
    public function delete($id)
    {
        $detail = $this->detailGroupUser->getDetailGroupUser($id);
        if (!$detail) {
            return response()->json(['message' => 'Not found user or group to delete'], 404);
        }
        $this->detailGroupUser->deleteDetailGroupUser($detail->id);
        return response()->json(['message' => 'Delete user from group successful']);
    }
}
