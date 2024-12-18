<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Interfaces\WarningsInterface;
use Illuminate\Http\Request;

class WarningsController extends Controller
{
    private $warnings, $user, $post;
    public function __construct(WarningsInterface $warningsInterface, UserInterface $userInterface, PostInterface $postInterface)
    {
        $this->warnings = $warningsInterface;
        $this->user = $userInterface;
        $this->post = $postInterface;
    }
    public function getWarningsOfUser($id)
    {
        $user = $this->user->getUser($id);
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $warnings = $this->warnings->getAllWarningsOfUser($id);
        return response()->json($warnings);
    }
    public function getWarningsOfPost($id)
    {
        $post = $this->post->getPost($id);
        if (!$post) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $warnings = $this->warnings->getAllWarningsOfPost($id);
        return response()->json($warnings);
    }
    public function reportPost(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $post = $this->post->getPost($id);
        if (!$post) {
            return response()->json(['message' => 'Not found this post'], 404);
        }
        $request->validate([
            'description' => 'required'
        ]);
        if (!$this->warnings->checkMaxQuantityReport($post->id, $user->id)) {
            return response()->json(['message' => 'You reported max quantity'], 404);
        }
        $warnings = $this->warnings->insertWarnings([
            'description' => $request->get('description'),
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
        return response()->json($warnings);
    }
}
