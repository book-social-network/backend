<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\DetailAuthorType;
use App\Repositories\Interfaces\AuthorInterface;
use App\Repositories\Interfaces\DetailAuthorBookInterface;
use App\Repositories\Interfaces\DetailAuthorTypeInterface;
use App\Repositories\Interfaces\TypeInterface;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    private $author;
    private $type;
    private $detailAuthorType;
    public function __construct(AuthorInterface $authorInterface, TypeInterface $typeInterface,DetailAuthorTypeInterface $detailAuthorTypeInterface){
        $this->author=$authorInterface;
        $this->type=$typeInterface;
        $this->detailAuthorType=$detailAuthorTypeInterface;
    }
    public function index(){
        $authors=$this->author->getAllAuthors();
        return response()->json($authors);
    }
    public function getAuthor($id){
        $author=$this->author->getAuthor($id);
        return response()->json($author);
    }
    public function insert(Request $request){
        $request->validate([
            'name' => 'required|string',
            'image' => 'required'
        ]);

        $author=$this->author->insertAuthor($request->all());
        return response()->json($author);
    }
    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string',
        ]);
        $author=$this->author->getAuthor($id);
        if(!$author){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        $this->author->updateAuthor($request->all(),$id);
        return response()->json(['message' => 'Update author successful']);
    }
    public function delete($id){
        $author=$this->author->getAuthor($id);
        if(!$author){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        $this->author->deleteAuthor($id);
        return response()->json(['message' => 'Delete author successful']);
    }
    public function insertTypeBookForAuthor(Request $request){
        $request->validate([
            'type_id' => 'required|integer',
            'author_id' => 'required|integer'
        ]);
        $type=$this->type->getType($request->get('type_id'));
        $author=$this->author->getAuthor($request->get('author_id'));
        if(!$author || !$type){
            return response()->json(['message' => 'Not found author or type book'], 404);
        }
        $this->detailAuthorType->insertDetailAuthorType([
            'author_id' => $author->id,
            'type_id' => $type->id
        ]);
        return response()->json(['message' => 'Insert type for author successful']);
    }
    public function deleteTypeBookForAuthor($id){
        $detail=$this->detailAuthorType->getDetailAuthorType($id);
        if(!$detail){
            return response()->json(['message' => 'Not found type book of book'], 404);
        }
        $this->detailAuthorType->deleteDetailAuthorType($detail->id);
        return response()->json(['message' => 'Insert type for author successful']);
    }
    public function getAllTypeOfAuthor($idAuthor){
        $types=$this->detailAuthorType->getAllTypeWithAuthor($idAuthor);
        if(!$types){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json($types);
    }
}
