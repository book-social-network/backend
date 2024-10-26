<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\DetailPostBook;
use App\Models\Post;
use App\Repositories\Interfaces\DetailPostBookInterface;

class DetailPostBookRepository implements DetailPostBookInterface{
    public function getAllPostOfBook($idBook){
        $book=Book::find($idBook);
        return $book->post()->get();
    }
    public function getBookOfPost($idPost){
        $post=Post::find($idPost);
        return $post->book()->get();
    }
    public function getDetailPostBook($id){
        $DetailPostBook=DetailPostBook::find($id);
        return empty($DetailPostBook) ? $DetailPostBook : null;
    }
    public function insertDetailPostBook($data){
        return DetailPostBook::create($data);
    }
    public function deleteDetailPostBook($id){
        $DetailPostBook=DetailPostBook::find($id);
        if(!empty($DetailPostBook)){
            $DetailPostBook->delete();
        }
    }
}
