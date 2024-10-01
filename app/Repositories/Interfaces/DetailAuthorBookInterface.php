<?php

namespace App\Repositories\Interfaces;

interface DetailAuthorBookInterface
{
    public function getAllBookOfAuthor($idAuthor);
    public function getAllAuthorOfBook($idBook);
    public function insertDetailAuthorBook($data);
    public function deleteDetailAuthorBook($id);
}
