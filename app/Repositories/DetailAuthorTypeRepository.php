<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\DetailAuthorType;
use App\Models\Type;
use App\Repositories\Interfaces\DetailAuthorTypeInterface;

class DetailAuthorTypeRepository implements DetailAuthorTypeInterface{
    public function getDetailAuthorType($id){
        return DetailAuthorType::find($id);
    }
    public function getAllTypeWithAuthor($idAuthor){
        $author=Author::find($idAuthor);
        return $author->type()->get();
    }
    public function getAllAuthorWithType($idType){
        $type=Type::find($idType);
        return $type->author()->get();
    }
    public function insertDetailAuthorType($data){
        return DetailAuthorType::create($data);
    }
    public function deleteDetailAuthorType($id){
        $DetailAuthorType=DetailAuthorType::find($id);
        if(!empty($DetailAuthorType)){
            $DetailAuthorType->delete();
        }
    }
}
