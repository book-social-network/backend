<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentInterface;

class CommentRepository implements CommentInterface{
    public function getComment($id){
        return Comment::find($id);
    }

    public function getAllCommentByUser($idUser){
        return Comment::where('user_id', $idUser)->orderBy('created_at', 'desc')->get();
    }
    public function getAllCommentOnPost($idPost){
        return Comment::where('post_id', $idPost)->orderBy('created_at', 'desc')->get();
    }
    public function insertComment($data){
        return Comment::create($data);
    }
    public function updateComment($data, $id){
        $Comment=Comment::find($id);
        $Comment->update($data);
        return $Comment;
    }
    public function deleteComment($id){
        $Comment=Comment::find($id);
        if(!empty($Comment)){
            $Comment->delete();
        }
    }

}
