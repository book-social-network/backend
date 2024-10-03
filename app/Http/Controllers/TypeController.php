<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DetailAuthorTypeInterface;
use App\Repositories\Interfaces\DetailBookTypeInterface;
use App\Repositories\Interfaces\TypeInterface;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    private $type;
    private $detailAuthorType;
    private $detailBookType;
    public function __construct(TypeInterface $typeInterface, DetailBookTypeInterface $detailBookTypeInterface, DetailAuthorTypeInterface $detailAuthorTypeInterface){
        $this->type=$typeInterface;
        $this->detailBookType=$detailBookTypeInterface;
        $this->detailAuthorType=$detailAuthorTypeInterface;
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
    // author
    public function getAllAuthorOfType($idType){
        $authors=$this->detailAuthorType->getAllAuthorWithType($idType);
        if(!$authors){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json($authors);
    }
    //book
    public function getAllBookOfType($idType){
        $book=$this->detailBookType->getAllBookOfType($idType);
        if(!$book){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json($book);
    }
}
