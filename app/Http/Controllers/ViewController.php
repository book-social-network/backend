<?php

namespace App\Http\Controllers;

use App\Models\View;
use App\Repositories\Interfaces\GroupInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    private $post, $user, $group;
    public function __construct(PostInterface $postInterface, UserInterface $userInterface, GroupInterface $groupInterface){
        $this->post=$postInterface;
        $this->user=$userInterface;
        $this->group=$groupInterface;
    }
    public function getTotalViews()
    {
        $todayStart = now()->startOfDay();
        $count = View::where('last_visited_at', '>=', $todayStart)->count();

        return response()->json(['total_views' => $count]);
    }

    public function getViewsByDay()
    {
        $views = View::selectRaw('DATE(last_visited_at) as date, COUNT(*) as count')
                     ->groupBy('date')
                     ->orderBy('date', 'desc')
                     ->get();

        return response()->json($views);
    }

    public function getViewsByWeek()
    {
        $views = View::selectRaw('WEEK(last_visited_at) as week, YEAR(last_visited_at) as year, COUNT(*) as count')
                     ->groupBy('week', 'year')
                     ->orderBy('year', 'desc')
                     ->orderBy('week', 'desc')
                     ->get();

        return response()->json($views);
    }

    public function getViewsByMonth()
    {
        $views = View::selectRaw('MONTH(last_visited_at) as month, YEAR(last_visited_at) as year, COUNT(*) as count')
                     ->groupBy('month', 'year')
                     ->orderBy('year', 'desc')
                     ->orderBy('month', 'desc')
                     ->get();

        return response()->json($views);
    }

    public function getViewsByYear()
    {
        $views = View::selectRaw('YEAR(last_visited_at) as year, COUNT(*) as count')
                     ->groupBy('year')
                     ->orderBy('year', 'desc')
                     ->get();

        return response()->json($views);
    }
    public function statistical(){
        $countPost=$this->post->getAllPost()->count();
        $countUser=$this->user->getAllUsers()->count();
        $countGroup=$this->group->getAllGroup()->count();
        return response()->json([$countGroup, $countUser, $countPost]);
    }
}
