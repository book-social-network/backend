<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AssessmentInterface;
use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    private $assessment, $book, $user;
    public function __construct(AssessmentInterface $assessmentInterface, BookInterface $bookInterface, UserInterface $userInterface){
        $this->assessment=$assessmentInterface;
        $this->book=$bookInterface;
        $this->user=$userInterface;
    }
    public function index(){
        $assessments=$this->assessment->getAllAssessments();
        return response()->json($assessments);
    }
    public function getAssessment($id){
        $assessment=$this->assessment->getAssessment($id);
        if(!$assessment){
            return response()->json(['message'=> 'Not found assessment']);
        }
        return response()->json($assessment);
    }
    public function insert(Request $request){
        $request->validate([
            'description' => 'required|string',
            'star'=> 'required',
            'book_id' =>'required',
        ]);
        $data=[
            'description' => $request->get('description'),
            'star' =>$request->get('star'),
            'book_id'=>$request->get('book_id'),
            'user_id'=>auth()->user()->id
        ];
        $assessment=$this->assessment->insertAssessment($data);
        return response()->json($assessment);
    }
    public function updateStateRead(Request $request,$idBook){
        $request->validate([
            'state_read' => 'required'
        ]);
        $book=$this->book->getBook($idBook);
        if(!$book){
            return response()->json(['message' => 'Not found book '], 404);
        }
        $user=auth()->user();
        if(!$user){
            return response()->json(['message' => 'Not found user '], 404);
        }
        $assessment=$this->assessment->getAssessmentWithIdBookAndUser($idBook,$user->id);
        if($assessment){
            $this->assessment->updateAssessment(['state_read' => $request->get('state_read')],$assessment->id);
        }else{
            $this->assessment->insertAssessment([
                'state_read' => $request->get('state_read'),
                'user_id' => $user->id,
                'book_id' => $book->id
            ]);
        }
        return response()->json(['message'=> 'Update state sucessful']);
    }
    public function update(Request $request,$id){
        $request->validate([
            'description' => 'required|string',
            'star'=> 'required',
            'book_id' =>'required',
        ]);
        $assessment=$this->assessment->getAssessment($id);
        if(!$assessment){
            return response()->json(['message'=> 'Not found assessment with id'],404);
        }
        $this->assessment->updateAssessment($request->all(),$assessment->id);
        return response()->json(['message' => 'Update assessment successful']);
    }
    public function delete($id){
        $assessment=$this->assessment->getAssessment($id);
        if(!$assessment){
            return response()->json(['message' => 'Not found assessment with id'], 404);
        }
        $this->assessment->deleteAssessment($id);
        return response()->json(['message' => 'Delete assessment successful']);
    }
    public function getAssessmentOfUser($idUser){
        $user=$this->user->getUser($idUser);
        if(!$user){
            return response()->json(['message' => 'Not found any assessment of user'], 404);
        }
        $assessment=$this->assessment->getAllAssessmentByUser($user->id);
        return response()->json($assessment);
    }
    public function getAssessmentOfBook($idBook){
        $book=$this->book->getbook($idBook);
        if(!$book){
            return response()->json(['message' => 'Not found any assessment of book'], 404);
        }
        $assessment=$this->assessment->getAllAssessmentByBook($book->id);
        return response()->json($assessment);
    }
}
