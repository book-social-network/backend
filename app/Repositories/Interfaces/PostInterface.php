<?php

namespace App\Repositories\Interfaces;

interface PostInterface
{
    public function getAllPostOnPage($page , $num);
    public function getAllPost();
    public function getPost($id);
    public function getAllPostByUser($id);
    public function getAllPostByUserNotInGroup($id);
    public function getAllPostInGroup($id, $page, $num);
    public function getAllPostGroupWithUser($idUser);
    public function insertPost($data);
    public function updatePost($data, $id);
    public function deletePost($id);
}
