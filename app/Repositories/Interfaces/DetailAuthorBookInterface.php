<?php

namespace App\Repositories\Interfaces;

interface DetailAuthorBookInterface
{
    public function getAllBookOfAuthor($idAuthor);
    public function getAllAuthorOfBook($idBook);
    public function getDetailAuthorBook($id);
    public function insertDetailAuthorBook($data);
    public function deleteDetailAuthorBook($id);
}
