<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\NotificationInterface;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $notification;
    public function __construct(NotificationInterface $notificationInterface){
        $this->notification = $notificationInterface;
    }
    public function index(){
        $user=auth()->user();
        if(empty($user)){
            return response()->json(['message' => 'Please login'],404);
        }
        $notifications=$this->notification->getAllNotificationOfUser($user->id);
        return response()->json($notifications);
    }
    public function updateState($id){
        $notification=$this->notification->getNotification($id);
        if(empty($notification)){
            return response()->json(['message' => 'Not found notification'],404);
        }
        $this->notification->updateNotification(['state'=>1],$notification->id);
        return response()->json(['message' => 'update notification successfully'],);
    }
    public function delete($id){
        $notification=$this->notification->getNotification($id);
        if(empty($notification)){
            return response()->json(['message' => 'Not found notification'],404);
        }
        $this->notification->deleteNotification($id);
        return response()->json(['message' => 'delete notification successfully'],);
    }
}
