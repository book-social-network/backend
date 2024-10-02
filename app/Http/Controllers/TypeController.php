<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\TypeInterface;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    private $type;
    public function __construct(TypeInterface $typeInterface){
        $this->type=$typeInterface;
    }
    public function index(){
        $types=$this->type->getAllType();
        return response()->json($types);
    }
    public function insert(Request $request){
        $request->validate([
            'name' => 'required|unique:types|string|max:255'
        ]);
        $type=$this->type->insertType($request->all());
        return response()->json($type);
    }

    public function update(Request $request,$id){
        $type=$this->type->getType($id);
        if (!$type) {
            return response()->json(['message' => 'Not found type with id'], 404);
        }
        $this->type->updateType($request->all(),$id);
        return response()->json(['message' => 'Type is updated']);
    }
    public function delete($id)
    {
        $type=$this->type->getType($id);
        if (!$type) {
            return response()->json(['message' => 'Not found type with id'], 404);
        }

        $this->type->deleteType($id);
        return response()->json(['message' => 'Type is deleted']);
    }
}
