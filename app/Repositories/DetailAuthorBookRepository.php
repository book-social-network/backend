<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Book;
use App\Models\DetailAuthorBook;
use App\Repositories\Interfaces\DetailAuthorBookInterface;

class DetailAuthorBookRepository implements DetailAuthorBookInterface{
    public function getAllBookOfAuthor($idAuthor){
        $author=Author::find($idAuthor);
        return $author->book()->get();
    }
    public function getAllAuthorOfBook($idBook){
        $book=Book::find($idBook);
        return $book->author()->get();
    }
    public function getDetailAuthorBook($id){
        return DetailAuthorBook::find($id);
    }
    public function insertDetailAuthorBook($data){
        DetailAuthorBook::create($data);
    }
    public function deleteDetailAuthorBook($id){
        $DetailAuthorBook=DetailAuthorBook::find($id);
        if(!empty($DetailAuthorBook)){
            $DetailAuthorBook->delete();
        }
    }

}
