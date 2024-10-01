<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentInterface;

class CommentRepository implements CommentInterface{
    public function getComment($id){
        return Comment::find($id);
    }

    public function getAllCommentByUser($idUser){
        return Comment::where('user_id', $idUser)->get();
    }
    public function getAllCommentOnPost($idPost){
        return Comment::where('post_id', $idPost)->get();
    }
    public function insertComment($data){
        Comment::create($data);
    }
    public function updateComment($data, $id){
        $Comment=Comment::find($id);
        $Comment->update($data);
    }
    public function deleteComment($id){
        $Comment=Comment::find($id);
        if(!empty($Comment)){
            $Comment->delete();
        }
    }

}
