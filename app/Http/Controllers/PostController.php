<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Events\LikeEvent;
use App\Events\NotificationSent;
use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\CommentInterface;
use App\Repositories\Interfaces\DetailGroupUserInterface;
use App\Repositories\Interfaces\DetailPostBookInterface;
use App\Repositories\Interfaces\LikeInterface;
use App\Repositories\Interfaces\NotificationInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $post, $book, $detailPostBook, $like, $comment, $notification, $user, $detailGroupUser;
    public function __construct(PostInterface $postInterface, BookInterface $bookInterface, DetailPostBookInterface $detailPostBookInterface, LikeInterface $likeInterface, CommentInterface $commentInterface, NotificationInterface $notificationInterface, UserInterface $userInterface, DetailGroupUserInterface $detailGroupUserInterface)
    {
        $this->post = $postInterface;
        $this->book = $bookInterface;
        $this->detailPostBook = $detailPostBookInterface;
        $this->like = $likeInterface;
        $this->comment = $commentInterface;
        $this->notification = $notificationInterface;
        $this->user = $userInterface;
        $this->detailGroupUser = $detailGroupUserInterface;
    }
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $posts = $this->post->getAllPost();
        $data = [];
        foreach ($posts as $post) {
            $check = true;
            if ($user->role != 'admin') {
                if ($post->detail_group_user_id != null) {
                    $group = $post->detail_group_user()->first()->group()->first();

                    $state = $group->state;
                    if (!$this->detailGroupUser->checkUserInGroup($post->detail_group_user_id, $user->id) && $state == 1) {
                        $check = false;
                    }
                }
            }
            if ($check) {
                $commemts = [];
                foreach ($post->comment()->get() as $comment) {
                    $commemts[] = [
                        'comment' => $comment,
                        'user' => $comment->user()->get()
                    ];
                }
                $books = $post->book()->get();
                $group = $post->detail_group_user_id != null ? $post->detail_group_user()->first()->group()->first() : null;
                $data[] = [
                    'post' => $post,
                    'user' => $post->user()->first(),
                    'books' => $books,
                    'group' => $group,
                    'commemts' => $commemts,
                    'likes' => $post->user_on_likes()->get(),
                    'state-like' => $this->like->getStateOfPost($post->id, auth()->user()->id)
                ];
            }
        }
        return response()->json($data);
    }
    public function getPostOnAllGroup()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $posts = $this->post->getAllPostGroupWithUser($user->id);
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
            $group = $post->detail_group_user_id != null ? $post->detail_group_user()->first()->group()->first() : null;

            $data[] = [
                'post' => $post,
                'user' => $post->user()->first(),
                'books' => $books,
                'group' => $group,
                'commemts' => $commemts,
                'likes' => $post->user_on_likes()->get(),
                'state-like' => $this->like->getStateOfPost($post->id, auth()->user()->id)
            ];
        }
        return response()->json($data);
    }
    public function getPost($id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $post = $this->post->getPost($id);
        if (!$post) {
            return response()->json(['message' => 'Not found post'], 404);
        }
        if ($post->detail_group_user_id != null) {
            $state = $post->detail_group_user()->first()->group()->first()->state;
            if (!$this->detailGroupUser->checkUserInGroup($post->detail_group_user_id, $user->id) && $state == 1) {
                return response()->json(['message' => 'Group is private! Please join in group before find this post.'], 404);
            }
        }
        $commemts = [];
        foreach ($post->comment()->get() as $comment) {
            $commemts[] = [
                'comment' => $comment,
                'user' => $comment->user()->get()
            ];
        }
        $group = $post->detail_group_user_id != null ? $post->detail_group_user()->first()->group()->first() : null;
        return response()->json([
            'post' => $post,
            'books' => $post->book()->get(),
            'user' => $post->user()->get(),
            'group' => $group,
            'comments' => $commemts,
            'likes' => $post->user_on_likes()->get(),
            'state-like' => $this->like->getStateOfPost($post->id, $user->id)
        ]);
    }
    public function insert(Request $request)
    {
        $user = auth()->user();
        if (empty($user)) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $request->validate([
            'description' => 'required|string',
        ]);
        $detail = null;
        if ($request->get('group_id')) {
            $detail = $this->detailGroupUser->getDetail($request->get('group_id'), $user->id);
        }
        $post = $this->post->insertPost([
            'description' => $request->get('description'),
            'detail_group_user_id' => $detail ? $detail->id : $detail,
            'user_id' => $user->id
        ]);

        return response()->json($post);
    }
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $request->validate([
            'description' => 'required|string',
        ]);
        $post = $this->post->getPost($id);
        if ($post->user_id != $user->id) {
            return response()->json(['message' => 'It is not your post'], 404);
        }
        if (!$post) {
            return response()->json(['message' => 'Not found post with id'], 404);
        }
        $this->post->updatePost($request->all(), $post->id);
        return response()->json(['message' => 'Update post successful']);
    }
    public function delete($id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $post = $this->post->getPost($id);
        if (!$post) {
            return response()->json(['message' => 'Not found post with id'], 404);
        }
        if ($post->user_id != $user->id) {
            return response()->json(['message' => 'Thlis post is not your post'], 404);
        } else if ($post->detail_group_user_id != null) {
            $group = $post->detail_group_user()->first()->group()->first();
            $admins = $this->detailGroupUser->getAdminGroup($group->id);
            foreach ($admins as $admin) {
                if ($admin->user_id == $user->id) {
                    $notification=$this->notification->insertNotification([
                        'from_id' => $group->id,
                        'to_id' => $user->id,
                        'information' => 'Bài viết của bạn đã bị xoá trong group ' . $group->name,
                        'from_type' => 'group',
                    ]);
                    broadcast(new NotificationSent($notification, $post->user_id));
                }
            }
        }
        $this->post->deletePost($id);
        return response()->json(['message' => 'Delete post successful']);
    }
    // Book
    public function insertBook(Request $request)
    {
        $request->validate([
            'post_id' => 'required|integer',
            'book_id' => 'required|integer'
        ]);
        $this->detailPostBook->insertDetailPostBook($request->all());
        return response()->json(['message' => 'Insert book in post successful']);
    }
    public function deleteBook(Request $request)
    {
        $request->validate([
            'post_id' => 'required|integer',
            'book_id' => 'required|integer'
        ]);
        $detail = $this->detailPostBook->getDetailPostBook($request->get('post_id'), $request->get('book_id'));
        if (!$detail) {
            return response()->json(['message' => 'Not found book in post with id'], 404);
        }
        $this->detailPostBook->deleteDetailPostBook($detail->id);
        return response()->json(['message' => 'Delete book in post successful']);
    }
    // Like
    public function insertLike(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $request->validate([
            'post_id' => 'required',
        ]);
        $post = $this->post->getPost($request->get('post_id'));
        if ($post == null) {
            return response()->json(['message' => 'Not found post'], 404);
        }
        if ($post->detail_group_user_id != null) {
            if (!$this->detailGroupUser->checkUserInGroup($post->detail_group_user_id, $user->id)) {
                return response()->json(['message' => 'User is not in a group'], 404);
            }
        }
        $this->like->insertLike([
            'post_id' => $request->get('post_id'),
            'user_id' => $user->id
        ]);

        // notification
        $notification = $this->notification->getNotificationWithPost($post->id, $user->id);
        $countCmt = $this->comment->getAllCommentOnPost($post->id)->count();
        $countLike = $this->like->getAllLikeOfPost($post->id)->count();
        broadcast(new LikeEvent($post->id, $countLike));
        if (empty($notification)) {
            $notification=$this->notification->insertNotification([
                'from_id' => $post->id,
                'to_id' => $user->id,
                'information' => 'Đã có ' . $countCmt . ' comment và ' . $countLike . ' like bài viết của bạn',
                'from_type' => 'post',
            ]);
            // handle Realtime notification
        } else {
            $notification=$this->notification->updateNotification([
                'information' => 'Đã có ' . $countCmt . ' comment và ' . $countLike . ' like bài viết của bạn',
            ], $notification->id);
        }
        broadcast(new NotificationSent($notification, $post->user_id));
        return response()->json(['message' => 'Like in post successful']);
    }
    public function deleteLike($idPost)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $like = $this->like->getLike($idPost, $user->id);
        if (!$like) {
            return response()->json(['message' => 'Not found book in post with id'], 404);
        }
        $this->like->deleteLike($idPost, $user->id);
        $countLike = $this->like->getAllLikeOfPost($idPost)->count();
        // handle Realtime like
        broadcast(new LikeEvent($idPost, $countLike));
        return response()->json(['message' => 'Delete like in post successful']);
    }
    // Comment
    public function insertComment(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $request->validate([
            'post_id' => 'required|integer',
            'description' => 'required'
        ]);
        $post = $this->post->getPost($request->get('post_id'));
        if ($post->detail_group_user_id != null) {
            if (!$this->detailGroupUser->checkUserInGroup($post->detail_group_user_id, $user->id)) {
                return response()->json(['message' => 'User is not in a group']);
            }
        }
        $cmt = $this->comment->insertComment([
            'description' => $request->get('description'),
            'post_id' => $request->get('post_id'),
            'user_id' => $user->id
        ]);
        // handle realtime comment
        broadcast(new CommentEvent($request->get('post_id'), $cmt));
        // notification
        $notification = $this->notification->getNotificationWithPost($post->id, $post->user_id);
        $countCmt = $this->comment->getAllCommentOnPost($post->id)->count();
        $countLike = $this->like->getAllLikeOfPost($post->id)->count();
        if (empty($notification)) {
            $notification=$this->notification->insertNotification([
                'from_id' => $post->id,
                'to_id' => $post->user_id,
                'information' => 'Đã có ' . $countCmt . ' comment và ' . $countLike . ' like bài viết của bạn',
                'from_type' => 'post',
            ]);
            // handle Realtime notification
            broadcast(new NotificationSent($notification, $post->user_id));
        } else {
            $notification=$this->notification->updateNotification([
                'from_id' => $post->id,
                'to_id' => $post->user_id,
                'information' => 'Đã có ' . $countCmt . ' comment và ' . $countLike . ' like bài viết của bạn',
                'from_type' => 'post',
            ], $notification->id);
            broadcast(new NotificationSent($notification, $post->user_id));
        }
        return response()->json(['message' => 'Comment in post successful']);
    }
    public function updateComment(Request $request, $id)
    {
        $request->validate([
            'description' => 'required'
        ]);
        $comments = $this->comment->getComment($id);
        if(!$comments){
            return response()->json(['message' => 'Not found comment'],404);
        }
        $cmt = $this->comment->updateComment($request->all(), $id);
        // handle realtime comment
        broadcast(new CommentEvent($comments->post_id, $cmt));
        return response()->json($comments);
    }
    public function deleteComment($id)
    {
        $comment = $this->comment->getComment($id);
        if (!$comment) {
            return response()->json(['message' => 'Not found book in post with id'], 404);
        }
        // handle realtime comment
        broadcast(new CommentEvent($comment->post_id, $comment));
        $this->comment->deleteComment($id);
        return response()->json(['message' => 'Delete comment in post successful']);
    }
}
