<?php

namespace App\Repositories\Interfaces;

interface NotificationInterface
{

    public function getAllNotificationOfUser($idUser);
    public function getNotification($id);
    public function insertNotification($data);
    public function deleteNotification($id);
}
