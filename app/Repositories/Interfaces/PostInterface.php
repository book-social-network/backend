<?php

namespace App\Repositories\Interfaces;

interface PostInterface
{
    public function getAllPostOnPage($page , $num);
    public function getAllPost();
    public function getAllPostNew();
    public function getPost($id);
    public function getAllPostByUser($id);
    public function getAllPostByUserNotInGroup($id);
    public function getAllPostInGroup($id, $page=null, $num=null);
    public function getAllPostGroupWithUser($idUser);
    public function getAllPostReport();
    public function insertPost($data);
    public function updatePost($data, $id);
    public function deletePost($id);
}
