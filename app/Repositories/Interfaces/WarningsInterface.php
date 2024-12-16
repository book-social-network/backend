<?php

namespace App\Repositories\Interfaces;

interface WarningsInterface
{
    public function getAllWarningsOfPost($idPost);
    public function getAllWarningsOfUser($idUser);
    public function checkMaxQuantityReport($idPost,$idUser);
    public function insertWarnings($data);
    public function deleteWarnings($idPost, $isUser);
}
