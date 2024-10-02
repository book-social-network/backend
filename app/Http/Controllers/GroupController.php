<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\GroupInterface;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    private $group;
    public function __construct(GroupInterface $groupInterface){
        $this->group=$groupInterface;
    }
    public function index(){
        $groups=$this->group->getAllGroup();
        return response()->json($groups);
    }
    public function insert(Request $request){
        $request->validate([
            'name' => 'required|unique:types|string|max:255'
        ]);
        $group=$this->group->insertGroup($request->all());
        return response()->json($group, 201);
    }
    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|unique:types|string|max:255',
            'state' => 'required'
        ]);
        $group=$this->group->getGroup($id);
        if (!$group) {
            return response()->json(['message' => 'Not found group with id'], 404);
        }
        $this->group->updateGroup($request->all(),$id);
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

}
