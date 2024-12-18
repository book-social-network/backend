<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AssessmentInterface;
use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\DetailAuthorBookInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    private $assessment, $book, $user, $detailAuthorBook;
    public function __construct(AssessmentInterface $assessmentInterface, BookInterface $bookInterface, UserInterface $userInterface, DetailAuthorBookInterface $detailAuthorBookInterface){
        $this->assessment=$assessmentInterface;
        $this->book=$bookInterface;
        $this->user=$userInterface;
        $this->detailAuthorBook=$detailAuthorBookInterface;
    }
    public function index(){
        $assessments=$this->assessment->getAllAssessments();
        $data=[];
        foreach($assessments as $assessment){
            $authors=$this->detailAuthorBook->getAllAuthorOfBook($assessment->book()->first()->id);
            $data[]= [
                'assessment'=> $assessment,
                'book' => $assessment->book()->get(),
                'user'=> $assessment->user()->get(),
                'authors'=> $authors
            ];
        }
        return response()->json($data);
    }
    public function getAssessment($id){
        $assessment=$this->assessment->getAssessment($id);
        if(!$assessment){
            return response()->json(['message'=> 'Not found assessment']);
        }
        $authors=$this->detailAuthorBook->getAllAuthorOfBook($assessment->book()->first()->id);
        return response()->json([
            'assessment'=> $assessment,
            'book' => $assessment->book()->get(),
            'user'=> $assessment->user()->get()['id'],
            'authors'=> $authors
        ]);
    }
    public function insert(Request $request){
        $request->validate([
            'description' => 'required|string',
            'book_id' =>'required',
        ]);
        $user=auth()->user();
        if(!$user){
            return response()->json(['message' => 'Please login'],404);
        }
        $book=$this->book->getBook($request->get('book_id'));
        if(!$book){
            return response()->json(['message' => 'Not found this book'],404);
        }
        $assessment=$this->assessment->getAssessmentWithIdBookAndUser($book->id,$user->id);
        if($assessment){
            return response()->json(['message' => 'You have rated this book'],404);
        }
        $data=[
            'description' => $request->get('description'),
            'star' =>$request->get('star')?$request->get('star'):null,
            'book_id'=>$book->id,
            'user_id'=>$user->id
        ];
        $assessment=$this->assessment->insertAssessment($data);
        $this->book->updateScore($book->id);
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
        if(!empty($assessment)){
            if(!empty($assessment->star) && $request->get('state_read')==0){
                return response()->json(['message'=> 'You can not update state after rating this book'], 404);
            }
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
            'star'=> 'required',
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
    public function getAssessmentOfUser($idUser, $state=null){
        $user=$this->user->getUser($idUser);
        if(!$user){
            return response()->json(['message' => 'Not found any assessment of user'], 404);
        }
        $assessments=$this->assessment->getAllAssessmentByUser($user->id);
        $data=[];
        if($state==null){
            foreach($assessments as $assessment){
                $authors=$this->detailAuthorBook->getAllAuthorOfBook($assessment->book()->first()->id);
                $data[]= [
                    'assessment'=> $assessment,
                    'book' => $assessment->book()->get(),
                    'user'=> $assessment->user()->get(),
                    'authors'=> $authors
                ];
            }
        }else{
            foreach($assessments as $assessment){
                $authors=$this->detailAuthorBook->getAllAuthorOfBook($assessment->book()->first()->id);
                if($assessment->state_read==$state){
                    $data[]= [
                        'assessment'=> $assessment,
                        'book' => $assessment->book()->get(),
                        'user'=> $assessment->user()->get(),
                        'authors'=> $authors
                    ];
                }
            }
        }

        return response()->json($data);
    }
    public function getAssessmentOfBook($idBook){
        $book=$this->book->getbook($idBook);
        if(!$book){
            return response()->json(['message' => 'Not found any assessment of book'], 404);
        }
        $assessments=$this->assessment->getAllAssessmentByBook($book->id);
        $data=[];
        foreach($assessments as $assessment){
            $authors=$this->detailAuthorBook->getAllAuthorOfBook($assessment->book()->first()->id);
            $data[]= [
                'assessment'=> $assessment,
                'book' => $assessment->book()->get(),
                'user'=> $assessment->user()->get(),
                'authors'=> $authors
            ];
        }
        return response()->json($data);
    }
}
