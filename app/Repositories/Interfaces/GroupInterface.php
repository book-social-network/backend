<?php

namespace App\Repositories\Interfaces;

interface GroupInterface
{
    public function getGroup($id);
    public function getAllGroup();
    public function insertGroup($data);
    public function updateGroup($data, $id);
    public function deleteGroup($id);
}
