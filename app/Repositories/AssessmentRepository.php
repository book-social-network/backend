<?php

namespace App\Repositories;

use App\Models\Assessment;
use App\Repositories\Interfaces\AssessmentInterface;

class AssessmentRepository implements AssessmentInterface{
    public function getAllAssessments(){
        return Assessment::get();
    }
    public function getAssessment($id){
        return Assessment::find($id);
    }
    public function getAssessmentWithIdBookAndUser($idBook, $idUser){
        return Assessment::where('user_id', $idUser)->where('book_id', $idBook);
    }
    public function getAllAssessmentByUser($idUser){
        return Assessment::where('user_id', $idUser)->get();
    }
    public function getAllAssessmentByBook($idBook){
        return Assessment::where('book_id', $idBook)->get();
    }
    public function insertAssessment($data){
        Assessment::create($data);
    }
    public function updateAssessment($data, $id){
        $assessment=Assessment::find($id);
        $assessment->update($data);
    }
    public function deleteAssessment($id){
        $assessment=Assessment::find($id);
        if(!empty($assessment)){
            $assessment->delete();
        }
    }
}
