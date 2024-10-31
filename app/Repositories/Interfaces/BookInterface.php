<?php

namespace App\Repositories\Interfaces;

interface BookInterface
{
    public function getAllBooks();
    public function getBook($id);
    public function insertBook($data);
    public function updateBook($data, $id);
    public function updateScore($id);
    public function deleteBook($id);
}
