<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\CommentInterface;
use App\Repositories\Interfaces\DetailPostBookInterface;
use App\Repositories\Interfaces\LikeInterface;
use App\Repositories\Interfaces\NotificationInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $post, $book, $detailPostBook, $like, $comment, $notification, $user;
    public function __construct(PostInterface $postInterface,BookInterface $bookInterface, DetailPostBookInterface $detailPostBookInterface, LikeInterface $likeInterface, CommentInterface $commentInterface, NotificationInterface $notificationInterface, UserInterface $userInterface){
        $this->post=$postInterface;
        $this->book=$bookInterface;
        $this->detailPostBook=$detailPostBookInterface;
        $this->like=$likeInterface;
        $this->comment=$commentInterface;
        $this->notification=$notificationInterface;
        $this->user=$userInterface;
    }
    public function index(){
        $posts=$this->post->getAllPost();
        $data=[];
        foreach($posts as $post){
            $commemts=[];
            foreach($post->comment()->get() as $comment){
                $commemts[]= [
                    'comment' => $comment,
                    'user' => $comment->user()->get()
                ];
            }
            $data[]= [
                'post' => $post,
                'commemts' => $commemts,
                'likes' => $post->user_on_likes()->get()
            ];
        }
        return response()->json($data);
    }
    public function getPost($id){
        $post=$this->post->getPost($id);
        if(!$post){
            return response()->json(['message' => 'Not found post'], 404);
        }
        $commemts=[];
        foreach($post->comment()->get() as $comment){
            $commemts[]= [
                'comment' => $comment,
                'user' => $comment->user()->get()
            ];
        }
        return response()->json([
            'post' => $post,
            'books' => $post->book()->get(),
            'user' => $post->user()->get(),
            'comments' => $commemts,
            'likes' => $post->user_on_likes()->get()
        ]);
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
    public function insertLike(Request $request){
        $request->validate([
            'post_id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);
        $post=$this->post->getPost($request->get('post_id'));
        if(!$this->post->checkUserInGroup($post->detail_group_user_id, $$request->get('user_id'))){
        return response()->json(['message'=> 'User is not in a group']);
        }
        $this->like->insertLike($request->all());
        // notification
        $notification=$this->notification->getNotificationWithPost($post->id);
        $countCmt=$this->comment->getAllCommentOnPost($post->id)->count();
        $countLike=$this->like->getAllLikeOfPost($post->id)->count();
        if(empty($notification)){
            $this->notification->insertNotification([
                'from_id' => $post->id,
                'to_id' => $request->get('user_id'),
                'information' => 'Đã có '.$countCmt.' comment và '.$countLike.' like bài viết của bạn',
                'from_type' => 'post',
            ]);
        }else{
            $this->notification->updateNotification([
                'information' => 'Đã có '.$countCmt.' comment và '.$countLike.' like bài viết của bạn',
            ],$post->id);
        }
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
    public function insertComment(Request $request){
        $request->validate([
            'post_id' => 'required|integer',
            'user_id' => 'required|integer',
            'description' => 'required'
        ]);
        $post=$this->post->getPost($request->get('post_id'));
        if(!$this->post->checkUserInGroup($post->detail_group_user_id, $$request->get('user_id'))){
            return response()->json(['message'=> 'User is not in a group']);
        }
        $this->comment->insertComment($request->all());
         // notification
         $notification=$this->notification->getNotificationWithPost($post->id);
         $countCmt=$this->comment->getAllCommentOnPost($post->id)->count();
         $countLike=$this->like->getAllLikeOfPost($post->id)->count();
         if(empty($notification)){
             $this->notification->insertNotification([
                 'from_id' => $post->id,
                 'to_id' => $request->get('user_id'),
                 'information' => 'Đã có '.$countCmt.' comment và '.$countLike.' like bài viết của bạn',
                 'from_type' => 'post',
             ]);
         }else{
             $this->notification->updateNotification([
                 'information' => 'Đã có '.$countCmt.' comment và '.$countLike.' like bài viết của bạn',
             ],$post->id);
         }
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
