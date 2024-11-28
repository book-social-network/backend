<?php

namespace App\Repositories\Interfaces;

interface NotificationInterface
{

    public function getAllNotificationOfUser($idUser,$page , $num);
    public function getNotification($id);
    public function getNotificationWithPost($idPost, $idUser);
    public function insertNotification($data);
    public function updateNotification($data, $id);
    public function deleteNotification($id);
    public function getNotificationsByUser($idUser,$page , $num);
    public function getNotificationsByGroup($idGroup,$page , $num);
    // quantity group
    public function getQuantityPageNotificationOfUser($idUser, $num);
    public function getQuantityPageNotificationByUser($idUser, $num);
    public function getQuantityPageNotificationByGroup($idUser, $num);
}
