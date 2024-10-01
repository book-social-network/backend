<?php

namespace App\Repositories\Interfaces;

interface ShareInterface
{
    public function getAllShareOfPost($idPost);
    public function getAllShareOfUser($idUser);
    public function insertShare($data);
    public function deleteShare($id);
}
