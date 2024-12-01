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
    public function getDetail($type,$book){
        return DetailBookType::where('type_id', $type)->where('book_id', $book)->first();
    }
    public function getAllBookOfType($idType){
        $type=Type::find($idType);
        return $type->book()->get();
    }
    public function insertDetailBookType($data){
        return DetailBookType::create($data);
    }
    public function deleteDetailBookType($id){
        $DetailBookType=DetailBookType::find($id);
        if(!empty($DetailBookType)){
            $DetailBookType->delete();
        }
    }
}
