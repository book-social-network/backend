<?php

namespace App\Repositories\Interfaces;

interface DetailPostBookInterface
{
    public function getAllPostOfBook($idBook);
    public function getBookOfPost($idPost);
    public function getDetailPostBook($id);
    public function insertDetailPostBook($data);
    public function deleteDetailPostBook($id);
}
