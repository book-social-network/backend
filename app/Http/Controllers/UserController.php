<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AssessmentInterface;
use App\Repositories\Interfaces\FollowInterface;
use App\Repositories\Interfaces\NotificationInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;
    public function __construct(UserInterface $userInterface){
        $this->user=$userInterface;
    }
    public function index(){
        $users=$this->user->getAllUsers();
        return response()->json($users);
    }
    public function getUser($id){
        $user=$this->user->getUser($id);
        if(!$user){
            return response()->json(['message'=> 'Not found user']);
        }
        return response()->json($user);
    }
    public function insert(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email'=> 'required|email',
            'password' => 'required|confirmed',
        ]);
        $user=$this->user->insertUser($request->all());
        return response()->json($user);
    }
    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string',
            'email'=> 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user=$this->user->getUser($id);
        if(!$user){
            return response()->json(['message'=> 'Not found user with id'],404);
        }
        $this->user->updateUser($request->all(),$user->id);
        return response()->json(['message' => 'Update user successful']);
    }
    public function delete($id){
        $user=$this->user->getUser($id);
        if(!$user){
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $this->user->deleteUser($id);
        return response()->json(['message' => 'Delete user successful']);
    }
}
