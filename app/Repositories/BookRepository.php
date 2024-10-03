<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Book;
use App\Repositories\Interfaces\BookInterface;

class BookRepository implements BookInterface{
    public function getAllBooks(){
        return Book::get();
    }
    public function getBook($id){
        return Book::find($id);
    }
    public function insertBook($data){
        return Book::create($data);
    }
    public function updateBook($data, $id){
        $book=Book::find($id);
        $book->update($data);
    }
    public function deleteBook($id){

        $book=Book::find($id);
        if(!empty($book)){
            $book->delete();
        }
    }

}
