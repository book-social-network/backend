<?php

namespace App\Repositories\Interfaces;

interface NotificationInterface
{

    public function getAllNotificationOfUser($idUser);
    public function getNotification($id);
    public function getNotificationWithPost($idPost);
    public function insertNotification($data);
    public function updateNotification($data, $id);
    public function deleteNotification($id);
    public function getNotificationsByUser($idUser);
    public function getNotificationsByGroup($idGroup);
}
