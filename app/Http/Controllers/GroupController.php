<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DetailGroupUserInterface;
use App\Repositories\Interfaces\GroupInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Cloudinary;
class GroupController extends Controller
{
    private $group;
    private $detailGroupUser, $post, $user;
    public function __construct(GroupInterface $groupInterface, DetailGroupUserInterface $detailGroupUserInterface, PostInterface $postInterface, UserInterface $userInterface){
        $this->group=$groupInterface;
        $this->detailGroupUser=$detailGroupUserInterface;
        $this->post=$postInterface;
        $this->user=$userInterface;
    }

    public function index(){
        $groups=$this->group->getAllGroup();
        return response()->json($groups);
    }

    public function insert(Request $request){
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $user = auth()->user();
        if(!$user){
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $group=$this->group->insertGroup($request->all());
        $data=[
            'group_id' => $group->id,
            'user_id' => $user->id,
            'state' => 1,
            'role' => 'admin'
        ];
        $this->detailGroupUser->insertDetailGroupUser($data);
        return response()->json($group);
    }

    public function update(Request $request,$id){
        $group=$this->group->getGroup($id);
        if (!$group) {
            return response()->json(['message' => 'Not found group with id'], 404);
        }
        $cloudinaryImage=null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->getRealPath();
            $uploadResponse = Cloudinary::upload($imagePath, [
                'folder' => 'group'
            ]);
            $cloudinaryImage = $uploadResponse->getSecurePath();
        }
        $this->group->updateGroup(array_merge(
            $request->all(),
            [
                'image_group' => $cloudinaryImage,
            ]
        ),$id);
        return response()->json(['message' => 'Information group is updated']);
    }

    public function delete($id)
    {
        $group=$this->group->getGroup($id);
        if (!$group) {
            return response()->json(['message' => 'Not found group with id'], 404);
        }

        $this->group->deleteGroup($id);
        return response()->json(['message' => 'Group is deleted']);
    }
    // Post
    public function getAllPostInGroup($id){
        $group=$this->group->getGroup($id);
        if (!$group) {
            return response()->json(['message' => 'Not found group with id'], 404);
        }
        $posts=$this->post->getAllPostInGroup($id);
        return response()->json($posts);
    }
}
