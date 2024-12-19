<?php

namespace App\Repositories;

use App\Models\Warnings;
use App\Repositories\Interfaces\WarningsInterface;

class WarningsRepository implements WarningsInterface
{
    public function getAllWarningsOfPost($idPost)
    {
        return Warnings::where('post_id', $idPost)->get();
    }
    public function getAllWarningsOfUser($idUser)
    {
        return Warnings::where('user_id', $idUser)->get();
    }
    public function checkMaxQuantityReport($idPost, $idUser)
    {
        return Warnings::where('post_id', $idPost)
            ->where('user_id', $idUser)
            ->get()->count() < 3;
    }
    public function insertWarnings($data)
    {
        return Warnings::create($data);
    }
    public function deleteWarnings($idPost, $isUser)
    {
        $Warnings = Warnings::where('post_id', $idPost)->where('user_id', $isUser)->first();
        if (!empty($Warnings)) {
            $Warnings->delete();
        }
    }
}
