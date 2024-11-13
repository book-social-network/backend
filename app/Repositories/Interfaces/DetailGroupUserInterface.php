<?php

namespace App\Repositories\Interfaces;

interface DetailGroupUserInterface
{
    public function getAllUserInGroup($idGroup);
    public function getAllGroupOfUser($idUser);
    public function insertDetailGroupUser($data);
    public function getAllDetailGroupUser();
    public function getDetailGroupUser($id);
    public function deleteDetailGroupUser($id);
    public function updateDetailGroupUser($data,$id);
    public function getAllUserWantToJoin($idGroup);
    public function checkUserInGroup($id, $idUser);
}
