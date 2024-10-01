<?php

namespace App\Repositories\Interfaces;

interface AuthorInterface
{
    public function getAllAuthors();
    public function getAuthor($id);
    public function insertAuthor($data);
    public function updateAuthor($data, $id);
    public function deleteAuthor($id);
}
