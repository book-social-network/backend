<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Repositories\Interfaces\CloudInterface;
use App\Repositories\Interfaces\FollowInterface;
use App\Repositories\Interfaces\LikeInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Validator;
use Cloudinary;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private $cloud, $user, $follow, $post, $like;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(CloudInterface $cloudInterface, UserInterface $userInterface, FollowInterface $followInterface, PostInterface $postInterface, LikeInterface $likeInterface)
    {
        $this->cloud = $cloudInterface;
        $this->user = $userInterface;
        $this->follow = $followInterface;
        $this->post = $postInterface;
        $this->like = $likeInterface;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = $this->user->getUserByEmail($request->get('email'));
        $this->user->updateUser([
            'lasted_login' => now(),
            'notified_inactive' => 0
        ], $user->id);
        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $cloudinaryImage = 'http://res.cloudinary.com/dpqqqawyw/image/upload/v1731144261/avatar/avatar-gender-neutral-silhouette-vector-600nw-2526512481_o4lren.webp';

        if ($request->hasFile('image')) {
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'), 'avatar');
        }

        $user = User::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'image_url' => $cloudinaryImage,
                'role' => $request->get('role')!=null?$request->get('role'):'member'
            ]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'image_url' => $cloudinaryImage,
            ]
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        $user = auth()->user();
        $groups = $user->group;
        $following = $this->follow->getAllUserFollow($user->id);
        $followers = $this->follow->getAllFollowOfUser($user->id);
        $posts = $this->post->getAllPostByUser($user->id);

        $dataFollowing = [];
        $dataFollower = [];
        foreach ($following as $follow) {
            $dataFollowing[] = $this->user->getUser($follow->follower);
        }
        foreach ($followers as $follow) {
            $dataFollower[] = $this->user->getUser($follow->user_id);
        }
        $data = [];
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
            'followers' => [
                'user' => $dataFollower,
                'quantity' => $followers->count()
            ],
            'following' => [
                'user' => $dataFollowing,
                'quantity' => $following->count()
            ],
            'posts' => $data,

        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            // 'expires_in' => 1,
            'user' => auth()->user()
        ]);
    }

    public function changePassWord(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|confirmed|string|min:6',
        ]);

        if ($request->get('old_password') == null || $request->get('new_password') == null) {
            return response()->json(['message' => 'Please enter fill full form'], 404);
        }
        $user = auth()->user();
        $user = User::where('email', $user->email)->first();
        if ($request->get('old_password') == $request->get('new_password')) {
            return response()->json(['message' => 'Please enter different password'], 404);
        }
        if (Hash::check($request->get('old_password'), $user->password)) {
            $user = User::where('id', $user->id)->update(
                ['password' => bcrypt($request->new_password)]
            );
            return response()->json([
                'message' => 'User successfully changed password',
                'user' => $user,
            ], 201);
        }
        // die($user);
        return response()->json(['message' => 'Password is incorrect'], 404);
    }

}
