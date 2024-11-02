<?php

namespace App\Http\Controllers;

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
    public function searh(Request $request){
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

}
