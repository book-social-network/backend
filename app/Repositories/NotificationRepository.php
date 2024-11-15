<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Interfaces\NotificationInterface;

class NotificationRepository implements NotificationInterface{
    public function getAllNotificationOfUser($idUser){
        return Notification::where('to_id', $idUser)->where('to_type','member')->get();
    }
    public function getNotification($id){
        return Notification::find($id);
    }
    public function getNotificationWithPost($idPost, $idUser){
        return Notification::where('from_id', $idPost)
        ->where('from_type', 'post')
        ->where('to_id', $idUser)
        ->first();
    }
    public function insertNotification($data){
        return Notification::create($data);
    }
    public function updateNotification($data, $id){
        $Notification=Notification::find($id);
        $Notification->update($data);
    }
    public function deleteNotification($id){
        $Notification=Notification::find($id);
        if(!empty($Notification)){
            $Notification->delete();
        }
    }
    public function getNotificationsByUser($idUser){
        $Notifications=Notification::where('to_id', $idUser)->get();
        if(!empty($Notification)){
            return $Notifications;
        }
    }
    public function getNotificationsByGroup($idGroup){
        $Notifications=Notification::where('to_id', $idGroup)->get();
        if(!empty($Notification)){
            return $Notifications;
        }
    }
}
