<?php

namespace App\Repositories\Interfaces;

interface AssessmentInterface
{
    public function getAllAssessments();
    public function getAssessment($id);
    public function getAssessmentWithIdBookAndUser($idBook, $idUser);
    public function getAllAssessmentByUser($idUser);
    public function getAllAssessmentByBook($idBook);
    public function insertAssessment($data);
    public function updateAssessment($data, $id);
    public function deleteAssessment($id);
}
