<?php

namespace App\Repositories\Interfaces;

interface TypeInterface
{
    public function getAllType();
    public function getType($id);
    public function getNameType($name);
    public function insertType($data);
    public function updateType($data, $id);
    public function deleteType($id);
}
