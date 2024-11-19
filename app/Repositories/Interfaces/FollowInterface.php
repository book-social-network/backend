<?php

namespace App\Repositories\Interfaces;

interface FollowInterface
{
    public function getAllFollowOfUser($idUser);
    public function getAllUserFollow($idUser);
    public function insertFollow($data);
    public function deleteFollow($id);
    public function getFollow($idFollower, $idUser);
    public function suggestFriends($userId);
}
