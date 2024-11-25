<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Repositories\Interfaces\CloudInterface;
use App\Repositories\Interfaces\DetailGroupUserInterface;
use App\Repositories\Interfaces\GroupInterface;
use App\Repositories\Interfaces\LikeInterface;
use App\Repositories\Interfaces\NotificationInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Cloudinary;

class GroupController extends Controller
{
    private $group;
    private $detailGroupUser, $post, $user, $cloud, $like, $notification;
    public function __construct(GroupInterface $groupInterface, DetailGroupUserInterface $detailGroupUserInterface, PostInterface $postInterface, UserInterface $userInterface, CloudInterface $cloudInterface, LikeInterface $likeInterface, NotificationInterface $notificationInterface)
    {
        $this->group = $groupInterface;
        $this->detailGroupUser = $detailGroupUserInterface;
        $this->post = $postInterface;
        $this->user = $userInterface;
        $this->like = $likeInterface;
        $this->notification = $notificationInterface;
    }

    public function index()
    {
        $groups = $this->group->getAllGroup();
        $data = [];
        foreach ($groups as $group) {
            $data[] = [
                'group' => $group,
                'users' => $group->user()->get()
            ];
        }
        return response()->json($data);
    }
    public function get($id)
    {
        $group = $this->group->getGroup($id);
        if (!$group) {
            return response()->json(['message' => 'Not found group with id'], 404);
        }
        $data = [];
        foreach ($group->user()->get() as $user) {
            $data[] = [
                'user' => $user,
                'role-in-group' => $this->detailGroupUser->getDetail($group->id, $user->id)->role
            ];
        }
        $countPost = $this->post->getAllPostInGroup($id)->count();
        return response()->json([
            'group' => $group,
            'users' => $data,
            'count-post' => $countPost
        ]);
    }
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required'
        ]);
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Not found user with id'], 404);
        }
        $cloudinaryImage = null;
        if ($request->hasFile('image')) {

            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'), 'group');
        }
        $group = $this->group->insertGroup(array_merge(
            $request->all(),
            [
                'image_group' => $cloudinaryImage,
            ]
        ));
        $data = [
            'group_id' => $group->id,
            'user_id' => $user->id,
            'state' => 1,
            'role' => 'admin'
        ];
        $admins = $this->detailGroupUser->getAdminGroup($group->id);
        foreach ($admins as $admin) {
            $this->notification->insertNotification([
                'from_id' => $group->id,
                'to_id' => $admin->id,
                'information' => $user->name . ' đã yêu cầu tham gia group ' . $group->name,
                'from_type' => 'group',
                'to_type' => 'user'
            ]);
            // notification realtime
            broadcast(new NotificationSent($user->name . ' đã yêu cầu tham gia group ' . $group->name, $admin->id));
        }
        $this->detailGroupUser->insertDetailGroupUser($data);
        return response()->json($group);
    }

    public function update(Request $request, $id)
    {
        $group = $this->group->getGroup($id);
        if (!$group) {
            return response()->json(['message' => 'Not found group with id'], 404);
        }
        $cloudinaryImage = $group->image_group;
        if ($request->hasFile('image')) {
            if ($group->image_group) {
                $this->cloud->deleteCloud($group->image_group);
            }
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'), 'group');
        }
        $this->group->updateGroup(array_merge(
            $request->all(),
            [
                'image_group' => $cloudinaryImage,
            ]
        ), $id);
        return response()->json(['message' => 'Information group is updated']);
    }

    public function delete($id)
    {
        $user = auth()->user();
        $group = $this->group->getGroup($id);
        if (!$group) {
            return response()->json(['message' => 'Not found group with id'], 404);
        }
        $admins = $this->detailGroupUser->getAdminGroup($group->id);
        if ($user->role != 'admin') {
            foreach ($admins as $admin) {
                if ($admin->user_id == $user->id) {
                    $this->cloud->deleteCloud($group->image_group);
                    $this->group->deleteGroup($id);
                    return response()->json(['message' => 'Group is deleted']);
                }
            }
        }
        return response()->json(['message' => 'You are not admin in group']);
    }
    // Post
    public function getAllPostInGroup($id)
    {
        $user = auth()->user();
        $group = $this->group->getGroup($id);
        if (!$group || !$user) {
            return response()->json(['message' => 'Not found group with id'], 404);
        }
        $detail = $this->detailGroupUser->getDetail($group->id, $user->id);
        if ($group->state == 1) {
            if ($detail == null) {
                return response()->json(['message' => 'You are not in group'], 404);
            } else if ($detail->state == 0) {
                return response()->json(['message' => 'You are not in group'], 404);
            }
        }
        $countPost = $this->post->getAllPostInGroup($id)->count();
        $posts = $this->post->getAllPostInGroup($id, 1, 10);
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
        return response()->json([
            'group' => $group,
            'users' => $group->user()->get(),
            'posts' => $data,
            'count-post' => $countPost
        ]);
    }
}
