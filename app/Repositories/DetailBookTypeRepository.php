<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\DetailBookType;
use App\Models\Type;
use App\Repositories\Interfaces\DetailBookTypeInterface;

class DetailBookTypeRepository implements DetailBookTypeInterface{
    public function getDetailBookType($id){
        return DetailBookType::find($id);
    }
    public function getAllTypeOfBook($idBook){
        $book=Book::find($idBook);
        return $book->type()->get();
    }
    public function getAllBookOfType($idType){
        $type=Type::find($idType);
        return $type->book()->get();
    }
    public function insertDetailBookType($data){
        DetailBookType::create($data);
    }
    public function deleteDetailBookType($id){
        $DetailBookType=DetailBookType::find($id);
        if(!empty($DetailBookType)){
            $DetailBookType->delete();
        }
    }
}
