<?php

namespace App\Repositories\Interfaces;

interface DetailBookTypeInterface
{
    public function getAllTypeOfBook($idBook);
    public function getAllBookOfType($idType);
    public function insertDetailBookType($data);
    public function deleteDetailBookType($id);
}
