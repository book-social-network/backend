<?php

namespace App\Repositories;

use App\Models\Type;
use App\Repositories\Interfaces\TypeInterface;

class TypeRepository implements TypeInterface{
    public function getAllType(){
        return Type::get();
    }
    public function insertType($data){
        Type::create($data);
    }
    public function updateType($data, $id){
        $Type=Type::find($id);
        $Type->update($data);
    }
    public function deleteType($id){

        $Type=Type::find($id);
        if(!empty($Type)){
            $Type->delete();
        }
    }

}
