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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user, $post, $comment, $like, $cloud, $follow;
    public function __construct(UserInterface $userInterface, PostInterface $postInterface, CommentInterface $commentInterface, LikeInterface $likeInterface, CloudInterface $cloudInterface, FollowInterface $followInterface)
    {
        $this->user = $userInterface;
        $this->post = $postInterface;
        $this->comment = $commentInterface;
        $this->like = $likeInterface;
        $this->cloud = $cloudInterface;
        $this->follow = $followInterface;
    }
    public function index()
    {
        $users = $this->user->getAllUsers();
        return response()->json($users);
    }
    public function geAllUsersNew()
    {
        $user=auth()->user();
        if(!$user){
            return response()->json(['message' => 'Please login'], 404);
        }
        if($user->role!='admin'){
            return response()->json(['message' => 'You are not admin'], 404);
        }
        $users = $this->user->getAllUsersNew();
        return response()->json($users);
    }
    public function getAllUsersOld()
    {
        $user=auth()->user();
        if(!$user){
            return response()->json(['message' => 'Please login'], 404);
        }
        if($user->role!='admin'){
            return response()->json(['message' => 'You are not admin'], 404);
        }
        $users = $this->user->getAllUsersOld();
        return response()->json($users);
    }
    public function getUser($id)
    {
        $auth=auth()->user();
        if (!$auth) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Not found user'], 404);
        }
        $groups=$user->group;
        $following=$this->follow->getAllUserFollow($user->id);
        $followers=$this->follow->getAllFollowOfUser($user->id);
        $posts=$this->post->getAllPostByUser($user->id);

        $dataFollowing=[];
        $dataFollower=[];
        foreach($following as $follow){
            $dataFollowing[]=$follow->user()->first();
        }
        foreach($followers as $follow){
            $dataFollower[]=$follow->user()->first();
        }
        $data=[];
        foreach ($posts as $post) {
            $commemts = [];
            foreach ($post->comment()->get() as $comment) {
                $commemts[] = [
                    'comment' => $comment,
                    'user' => $comment->user()->get()
                ];
            }
            $books = $post->book()->get();
            $postShare=null;
            $detail=null;
            if($post->share_id!=null){
                $postShare=$this->post->getPost($post->share_id);
                $detail=$postShare->detail_group_user()->first();
            }
            $data[] = [
                'post' => $post,
                'user' => $post->user()->first(),
                'books' => $books,
                'commemts' => $commemts,
                'likes' => $post->user_on_likes()->get(),
                'state-like' => $this->like->getStateOfPost($post->id, auth()->user()->id),
                'share' => $postShare==null ? null : [
                    'post' => $this->post->getPost($postShare->id),
                    'user' => $postShare->user()->first(),
                    'books' => $postShare->book()->get(),
                    'group' => $detail!=null?$detail->group()->first():null,
                ]
            ];
        }
        return response()->json([
            'user' => $user,
            'groups' => $groups,
            'followers'=>[
                'user' => $dataFollower,
                'quantity' => $followers->count()
            ],
            'following'=>[
                'user' => $dataFollowing,
                'quantity' => $following->count()
            ],
            'posts'=> $data,
            'state-follow'=>$this->follow->getFollow($user->id,$auth->id)!=null?1:0
        ]);
    }
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
        $cloudinaryImage = 'http://res.cloudinary.com/dpqqqawyw/image/upload/v1731144261/avatar/avatar-gender-neutral-silhouette-vector-600nw-2526512481_o4lren.webp';
        // Xử lý hình ảnh nếu có
        if ($request->hasFile('image')) {
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'), 'avatar');
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
    public function update(Request $request)
    {
        $auth=auth()->user();
        $user = null;
        if($auth->role=='admin'){
            $user=$this->user->getUser($request->get('id'));
        }else{
            $user=$auth;
        }

        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $cloudinaryImage = $user->image_url;
        $avatar = 'http://res.cloudinary.com/dpqqqawyw/image/upload/v1731144261/avatar/avatar-gender-neutral-silhouette-vector-600nw-2526512481_o4lren.webp';

        if ($request->hasFile('image')) {
            if ($user->image_url && $cloudinaryImage != $avatar) {
                $this->cloud->deleteCloud($user->image_url);
            }
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'), 'avatar');
        }
        if ($request->get('password') != null) {
            $this->user->updateUser(array_merge(
                $request->all(),
                [
                    'password' => bcrypt($request->password),
                    'image_url' => $cloudinaryImage,
                ]
            ), $user->id);
        } else {
            $this->user->updateUser(array_merge(
                $request->all(),
                [
                    'image_url' => $cloudinaryImage,
                ]
            ), $user->id);
        }

        return response()->json(['message' => 'Update user successful']);
    }
    public function updateDefaultImage()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $cloudinaryImage = $user->image_url;
        $avatar = 'http://res.cloudinary.com/dpqqqawyw/image/upload/v1731144261/avatar/avatar-gender-neutral-silhouette-vector-600nw-2526512481_o4lren.webp';

        if ($cloudinaryImage && $cloudinaryImage != $avatar) {
            $this->cloud->deleteCloud($cloudinaryImage);
        }
        $this->user->updateUser(
            [
                'image_url' => $avatar,
            ], $user->id);

        return response()->json(['message' => 'Update image default successful']);
    }
    public function updatePoint(Request $request){
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $request->validate([
            'point' => 'required|integer',
        ]);
        $user=$this->user->updateUser([
            'point'=> $request->get('point')
        ],$user->id);
        return response()->json($user);
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
        $auth = auth()->user();
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $posts = $this->post->getAllPostByUser($id);
        $data = [];
        foreach ($posts as $post) {
            $commemts = [];
            foreach ($post->comment() as $comment) {
                $commemts[] = [
                    'comment' => $comment,
                    'user' => $comment->user()->get()
                ];
            }
            $data[] = [
                'post' => $post,
                'books' => $post->book()->get(),
                'user' => $post->user()->get(),
                'comments' => $commemts,
                'likes' => $post->user_on_likes()->get(),
                'state-like' => $this->like->getStateOfPost($post->id, $auth->id)
            ];
        }
        return response()->json($data);
    }
    public function getAllPostUserFollow()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $followers = $this->follow->getAllFollowOfUser($user->id);
        $data = [];
        foreach ($followers as $follower) {
            $data[] = [
                'follower' => $follower,
                'posts' => $this->getAllPostOfUser($follower->follower)->original
            ];
        }
        return response()->json($data);
    }
    // Comment
    public function getAllComment($id)
    {
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $comments = $this->comment->getAllCommentByUser($id);
        $data = [];
        foreach ($comments as $comment) {
            $data[] = [
                'post' => $comment->post()->get(),
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
        $data = [];
        foreach ($likes as $like) {
            $data[] = [
                'post' => $like->post()->get(),
                'like' => $like
            ];
        }
        return response()->json($data);
    }
    public function forgetPassword(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        $user=$this->user->getUserByEmail($request->get('email'));
        if(empty($user)){
            return response()->json(['message' => 'Not found account with email'], 404);
        }
        $password = Str::random(6);
        // die($user);
        Mail::to($user->email)->send(new \App\Mail\ForgetPasswordNotification($user,$password));
        $user->update(
            ['password' => bcrypt($password)]
        );


        return response()->json([
            'message' => 'Create new password success',
            'user' => $user,
        ]);
    }
}
