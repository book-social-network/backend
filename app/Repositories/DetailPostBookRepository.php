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
    public function getDetailPostBook($idPost, $idBook){
        return DetailPostBook::where('book_id',$idBook)->where('post_id',$idPost)->first();
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
