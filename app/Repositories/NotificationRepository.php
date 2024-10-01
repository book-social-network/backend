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
    public function insertNotification($data){
        Notification::create($data);
    }
    public function deleteNotification($id){
        $Notification=Notification::find($id);
        if(!empty($Notification)){
            $Notification->delete();
        }
    }

}
