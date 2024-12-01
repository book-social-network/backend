<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Interfaces\NotificationInterface;

class NotificationRepository implements NotificationInterface{
    public function getQuantityNotification($idUser){
        return Notification::where('to_id', $idUser)
        ->where('to_type','member')
        ->orderBy('updated_at', 'asc')
        ->get()->count();
    }
    public function getAllNotificationOfUser($idUser,$page , $num){
        return Notification::where('to_id', $idUser)
        ->where('to_type','member')
        ->skip(($page - 1) * $num)->take($num)
        ->orderBy('updated_at', 'asc')
        ->get();
    }

    public function getNotification($id){
        return Notification::find($id);
    }
    public function getNotificationWithPost($idPost, $idUser){
        return Notification::where('from_id', $idPost)
        ->where('from_type', 'post')
        ->where('to_id', $idUser)
        ->orderBy('updated_at', 'asc')
        ->first();
    }
    public function insertNotification($data){
        return Notification::create($data);
    }
    public function updateNotification($data, $id){
        $Notification=Notification::find($id);
        $Notification->update($data);
        return $Notification;
    }
    public function deleteNotification($id){
        $Notification=Notification::find($id);
        if(!empty($Notification)){
            $Notification->delete();
        }
    }
    public function getNotificationsByUser($idUser,$page , $num){
        $Notifications=Notification::where('from_id', $idUser)
        ->where('from_type','member')
        ->orderBy('updated_at', 'asc')
        ->skip(($page - 1) * $num)->take($num)
        ->get();
        if(!empty($Notification)){
            return $Notifications;
        }
    }
    public function getNotificationsByGroup($idGroup,$page , $num){
        $Notifications=Notification::where('from_id', $idGroup)
        ->where('from_type','group')
        ->orderBy('updated_at', 'asc')
        ->skip(($page - 1) * $num)->take($num)
        ->get();
        if(!empty($Notification)){
            return $Notifications;
        }
    }
    // quantity page
    public function getQuantityPageNotificationOfUser($idUser, $num){
        $totalNotifications = Notification::where('to_id', $idUser)
            ->where('to_type', 'member')
            ->count();
        return ceil($totalNotifications / $num);
    }
    public function getQuantityPageNotificationByUser($idUser, $num){
        $totalNotifications = Notification::where('from_id', $idUser)
            ->where('from_type', 'member')
            ->orderBy('updated_at', 'asc')
            ->count();
        return ceil($totalNotifications / $num);
    }
    public function getQuantityPageNotificationByGroup($idUser, $num){
        $totalNotifications = Notification::where('from_id', $idUser)
            ->where('from_type', 'group')
            ->orderBy('updated_at', 'asc')
            ->count();
        return ceil($totalNotifications / $num);
    }
}
