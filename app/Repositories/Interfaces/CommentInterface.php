<?php

namespace App\Repositories\Interfaces;

interface CommentInterface
{
    public function getComment($id);
    public function getAllCommentByUser($idUser);
    public function getAllCommentOnPost($idPost);
    public function insertComment($data);
    public function updateComment($data, $id);
    public function deleteComment($id);
}
