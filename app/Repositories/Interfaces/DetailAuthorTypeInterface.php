<?php

namespace App\Repositories\Interfaces;

interface DetailAuthorTypeInterface
{
    public function getDetailAuthorType($id);
    public function getAllAuthorWithType($idType);
    public function getAllTypeWithAuthor($idAuthor);
    public function insertDetailAuthorType($data);
    public function deleteDetailAuthorType($id);
}
