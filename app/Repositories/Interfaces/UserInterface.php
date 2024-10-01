<?php
namespace App\Repositories\Interfaces;
interface UserInterface{
    public function getAllUsers();
    public function getUser($id);
    public function getUserByEmail($email);
    public function insertUser($data);
    public function updateUser($data,$id);
    public function deleteUser($id);
}
