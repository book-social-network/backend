<?php

namespace App\Repositories\Interfaces;

interface DetailGroupUserInterface
{
    public function getAllUserInGroup($idGroup);
    public function getAllGroupOfUser($idUser);
    public function insertDetailGroupUser($data);
    public function deleteDetailGroupUser($id);
}
