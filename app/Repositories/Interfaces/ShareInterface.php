<?php
namespace App\Repositories\Interfaces;
interface ShareInterface
{
    public function getAllShare();
    public function getShare($id);
    public function getAllShareOfBook($idBook);
    public function getAllShareOfUser($idUser);
    public function insertShare($data);
    public function deleteShare($id);
}
