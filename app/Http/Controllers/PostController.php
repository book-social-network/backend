<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\CommentInterface;
use App\Repositories\Interfaces\DetailPostBookInterface;
use App\Repositories\Interfaces\LikeInterface;
use App\Repositories\Interfaces\PostInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $post, $book, $detailPostBook, $like, $comment;
    public function __construct(PostInterface $postInterface,BookInterface $bookInterface, DetailPostBookInterface $detailPostBookInterface, LikeInterface $likeInterface, CommentInterface $commentInterface){
        $this->post=$postInterface;
        $this->book=$bookInterface;
        $this->detailPostBook=$detailPostBookInterface;
        $this->like=$likeInterface;
        $this->comment=$commentInterface;
    }
    public function index(){
        $posts=$this->post->getAllPost();
        return response()->json($posts);
    }
    public function getPost($id){
        $post=$this->post->getPost($id);
        $books=$this->detailPostBook->getBookOfPost($post->id);
        if(!$post){
            return response()->json(['message' => 'Not found post'], 404);
        }
        return response()->json([$post, $books]);
    }
    public function insert(Request $request){
        $request->validate([
            'description' => 'required|string',
            'user_id' =>'required'
        ]);
        $post=$this->post->insertPost($request->all());
        return response()->json($post);
    }
    public function update(Request $request,$id){
        $request->validate([
            'description' => 'required|string',
            'user_id' =>'required'
        ]);
        $post=$this->post->getPost($id);
        if(!$post){
            return response()->json(['message'=> 'Not found post with id'],404);
        }
        $this->post->updatePost($request->all(),$post->id);
        return response()->json(['message' => 'Update post successful']);
    }
    public function delete($id){
        $post=$this->post->getPost($id);
        if(!$post){
            return response()->json(['message' => 'Not found post with id'], 404);
        }
        $this->post->deletePost($id);
        return response()->json(['message' => 'Delete post successful']);
    }
    // Book
    public function getBookOfPost($idPost){
        $post=$this->post->getPost($idPost);
        if(!$post){
            return response()->json(['message'=> 'Not found post with id'],404);
        }
        $books=$this->detailPostBook->getBookOfPost($idPost);
        return response()->json($books);
    }
    public function insertBook(Request $request){
        $request->validate([
            'post_id' => 'required|integer',
            'book_id' => 'required|integer'
        ]);
        $this->detailPostBook->insertDetailPostBook($request->all());
        return response()->json(['message'=> 'Insert book in post successful']);
    }
    public function deleteBook($id){
        $detail=$this->detailPostBook->getDetailPostBook($id);
        if(!$detail){
            return response()->json(['message'=> 'Not found book in post with id'],404);
        }
        $this->detailPostBook->deleteDetailPostBook($id);
        return response()->json(['message'=> 'Delete book in post successful']);
    }
    // Like
    public function getAllLike($idPost){
        $post=$this->post->getPost($idPost);
        if(!$post){
            return response()->json(['message'=> 'Not found post'], 404);
        }
        $likes=$this->like->getAllLikeOfPost($idPost);
        return response()->json($likes);
    }
    public function insertLike(Request $request){
        $request->validate([
            'post_id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);
        $this->like->insertLike($request->all());
        return response()->json(['message'=> 'Like in post successful']);
    }
    public function deleteLike($id){
        $like=$this->like->getLike($id);
        if(!$like){
            return response()->json(['message'=> 'Not found book in post with id'],404);
        }
        $this->like->deleteLike($id);
        return response()->json(['message'=> 'Delete like in post successful']);
    }
    // Comment
    public function getAllComment($idPost){
        $post=$this->post->getPost($idPost);
        if(!$post){
            return response()->json(['message'=> 'Not found post'], 404);
        }
        $comments=$this->comment->getAllCommentOnPost($idPost);
        return response()->json($comments);
    }
    public function insertComment(Request $request){
        $request->validate([
            'post_id' => 'required|integer',
            'user_id' => 'required|integer',
            'description' => 'required'
        ]);
        $this->comment->insertComment($request->all());
        return response()->json(['message'=> 'Comment in post successful']);
    }
    public function updateComment(Request $request, $id){
        $request->validate([
            'post_id' => 'required|integer',
            'user_id' => 'required|integer',
            'description' => 'required'
        ]);
        $comments=$this->comment->getComment($id);
        $this->comment->updateComment($request->all(), $id);
        return response()->json($comments);
    }
    public function deleteComment($id){
        $comment=$this->comment->getComment($id);
        if(!$comment){
            return response()->json(['message'=> 'Not found book in post with id'],404);
        }
        $this->comment->deleteComment($id);
        return response()->json(['message'=> 'Delete comment in post successful']);
    }
}
