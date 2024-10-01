<?php

namespace App\Repositories\Interfaces;

interface PostInterface
{
    public function getPost($id);
    public function insertPost($data);
    public function updatePost($data, $id);
    public function deletePost($id);
}
