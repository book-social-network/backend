<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Type;
use App\Repositories\Interfaces\AuthorInterface;

class AuthorRepository implements AuthorInterface{
    public function getAllAuthors(){
        return Author::get();
    }
    public function getAuthor($id){
        return Author::find($id);
    }
    public function insertAuthor($data){
        return Author::create($data);
    }
    public function updateAuthor($data, $id){
        $author=Author::find($id);
        $author->update($data);
    }
    public function deleteAuthor($id){
        $author=Author::find($id);
        if(!empty($author)){
            $author->delete();
        }
    }

}
