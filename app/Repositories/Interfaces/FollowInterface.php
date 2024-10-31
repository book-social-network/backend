<?php

namespace App\Repositories\Interfaces;

interface FollowInterface
{
    public function getAllFollowOfUser($idUser);
    public function insertFollow($data);
    public function deleteFollow($id);
    public function getFollow($id);
    public function suggestFriends($userId);
}
