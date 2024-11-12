<?php

namespace App\Repositories\Interfaces;

interface LikeInterface
{
    public function getAllLikeOfPost($idPost);
    public function getAllLikeOfUser($idUser);
    public function getLike($id);
    public function getStateOfPost($idPost, $isUser);
    public function insertLike($data);
    public function deleteLike($id);
}
