<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AssessmentInterface;
use App\Repositories\Interfaces\CloudInterface;
use App\Repositories\Interfaces\CommentInterface;
use App\Repositories\Interfaces\FollowInterface;
use App\Repositories\Interfaces\LikeInterface;
use App\Repositories\Interfaces\NotificationInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\UserInterface;
use Cloudinary;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user, $post, $comment, $like, $cloud;
    public function __construct(UserInterface $userInterface, PostInterface $postInterface, CommentInterface $commentInterface, LikeInterface $likeInterface, CloudInterface $cloudInterface)
    {
        $this->user = $userInterface;
        $this->post = $postInterface;
        $this->comment = $commentInterface;
        $this->like = $likeInterface;
        $this->cloud = $cloudInterface;
    }
    public function index()
    {
        $users = $this->user->getAllUsers();
        return response()->json($users);
    }
    public function getUser($id)
    {
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Not found user'],404);
        }
        return response()->json($user);
    }
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
        $cloudinaryImage = 'http://res.cloudinary.com/dpqqqawyw/image/upload/v1729268122/149071_hh2iuh.png';
        // Xử lý hình ảnh nếu có
        if ($request->hasFile('image')) {
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'),'avatar');
        }

        $user = $this->user->insertUser(array_merge(
            $request->all(),
            [
                'password' => bcrypt($request->password),
                'image_url' => $cloudinaryImage,
            ]
        ));
        return response()->json($user);
    }
    public function update(Request $request, $id)
    {
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $cloudinaryImage = $user->image_url;
        if ($request->hasFile('image')) {
            if ($user->image_url) {
                $this->cloud->deleteCloud($user->image_url);
            }
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'),'avatar');
        }
        $this->user->updateUser(array_merge(
            $request->all(),
            [
                'password' => bcrypt($request->password),
                'image_url' => $cloudinaryImage,
            ]
        ), $user->id);
        return response()->json(['message' => 'Update user successful']);
    }
    public function delete($id)
    {
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $this->cloud->deleteCloud($user->image_url);
        $this->user->deleteUser($id);
        return response()->json(['message' => 'Delete user successful']);
    }
    // Post
    public function getAllPostOfUser($id)
    {
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $posts = $this->post->getAllPostByUser($id);
        $data=[];
        foreach($posts as $post){
            $commemts=[];
            foreach($post->comment() as $comment){
                $commemts[]= [
                    'comment' => $comment,
                    'user' => $comment->user()
                ];
            }
            $data[]= [
                'post' => $post,
                'comments' => $commemts,
                'likes' => $post->user_on_likes()
            ];
        }
        return response()->json($posts);
    }
    // Comment
    public function getAllComment($id)
    {
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $comments = $this->comment->getAllCommentByUser($id);
        $data=[];
        foreach($comments as $comment){
            $data[]= [
                'post' => $comment->post(),
                'comment' => $comment
            ];
        }
        return response()->json($data);
    }
    // Like
    public function getAllLike($id)
    {
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $likes = $this->like->getAllLikeOfUser($id);
        $data=[];
        foreach($likes as $like){
            $data[]= [
                'post' => $like->post(),
                'like' => $like
            ];
        }
        return response()->json($data);
    }
}
