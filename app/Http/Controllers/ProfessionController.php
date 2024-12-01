<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\GroupInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    private $user,$group,$book;
    public function __construct(UserInterface $userInterface, GroupInterface $groupInterface, BookInterface $bookInterface){
        $this->user=$userInterface;
        $this->group=$groupInterface;
        $this->book=$bookInterface;
    }
    public function search(Request $request){
        $search=$request->input('search',null);
        $type=$request->input('type','book');
        $results=null;
        if($search==null){
            return response()->json(['message'=> 'Not found value in search'],404);
        }
        if($type=='book'){
            $results=$this->book->getByName($search);
        }else if($type=='user'){
            $results=$this->user->getByName($search);
        }else{
            $results=$this->group->getByName($search);
        }
        if(empty($results)){
            return response()->json(['message'=> 'Not found value with'.$type.' in search'],404);
        }
        return response()->json($results);
    }
    public function sort(Request $request){
        $sort=$request->input('sort', 'ratings');
        if($sort==null){
            return response()->json(['message'=> 'Not found value in search'],404);
        }
        if($sort=='ratings'){
            return Book::orderBy('ratings', 'desc')
            ->get();
        }else if($sort=='posts'){
            return Book::withCount('posts')->orderBy('posts_count', 'desc')->get();
        }
        return response()->json(['message'=> 'Not found value in search'],404);
    }
    public function getAllPoint(){
        $points=$this->user->getAllPointOfUsers();
        return response()->json($points);
    }
}
