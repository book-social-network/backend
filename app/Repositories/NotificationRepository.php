<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Interfaces\NotificationInterface;

class NotificationRepository implements NotificationInterface{
    public function getAllNotificationOfUser($idUser){
        return Notification::where('user_id', $idUser)->get();

    }
    public function getNotification($id){
        return Notification::find($id);
    }
    public function getNotificationWithPost($idPost){
        return Notification::where('from_id', $idPost)->where('from_type', 'post')->get();
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
        $Notifications=Notification::where('to_id', $idUser);
        if(!empty($Notification)){
            return $Notifications;
        }
    }
    public function getNotificationsByGroup($idGroup){
        $Notifications=Notification::where('to_id', $idGroup);
        if(!empty($Notification)){
            return $Notifications;
        }
    }
}
