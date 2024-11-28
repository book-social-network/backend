<?php

namespace App\Http\Controllers;

use App\Models\View;
use App\Repositories\Interfaces\AuthorInterface;
use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\GroupInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\TypeInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    private $post, $user, $group, $book, $author, $type;
    public function __construct(PostInterface $postInterface, UserInterface $userInterface, GroupInterface $groupInterface, BookInterface $bookInterface, AuthorInterface $authorInterface, TypeInterface $typeInterface){
        $this->post=$postInterface;
        $this->user=$userInterface;
        $this->group=$groupInterface;
        $this->book=$bookInterface;
        $this->author=$authorInterface;
        $this->type=$typeInterface;
    }
    public function getTotalViews()
    {
        $count = View::get()->count();

        return response()->json(['total_views' => $count]);
    }

    public function getViewsByDay()
    {
        $views = View::selectRaw('DATE(last_visited_at) as date, COUNT(*) as count')
                     ->groupBy('date')
                     ->orderBy('date', 'asc')
                     ->get();

        return response()->json($views);
    }

    public function getViewsByWeek()
    {
        $views = View::selectRaw('WEEK(last_visited_at) as week, YEAR(last_visited_at) as year, COUNT(*) as count')
                     ->groupBy('week', 'year')
                     ->orderBy('year', 'asc')
                     ->orderBy('week', 'asc')
                     ->get();

        return response()->json($views);
    }

    public function getViewsByMonth()
    {
        $views = View::selectRaw('MONTH(last_visited_at) as month, YEAR(last_visited_at) as year, COUNT(*) as count')
                     ->groupBy('month', 'year')
                     ->orderBy('year', 'asc')
                     ->orderBy('month', 'asc')
                     ->get();

        return response()->json($views);
    }

    public function getViewsByYear()
    {
        $views = View::selectRaw('YEAR(last_visited_at) as year, COUNT(*) as count')
                     ->groupBy('year')
                     ->orderBy('year', 'asc')
                     ->get();

        return response()->json($views);
    }
    public function statistical(){
        $countPost=$this->post->getAllPost()->count();
        $countUser=$this->user->getAllUsers()->count();
        $countGroup=$this->group->getAllGroup()->count();
        $countBook=$this->book->getAllBooks()->count();
        $countAuthor=$this->author->getAllAuthors()->count();
        $countType=$this->type->getAllType()->count();
        return response()->json([
            'posts' => $countPost,
            'users' => $countUser,
            'groups' => $countGroup,
            'books' => $countBook,
            'authors' => $countAuthor,
            'types' => $countType,
        ]);
    }
}
