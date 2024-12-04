<?php

namespace App\Repositories;

use App\Models\Type;
use App\Repositories\Interfaces\TypeInterface;

class TypeRepository implements TypeInterface{
    public function getAllType(){
        return Type::get();
    }
    public function getType($id){
        return Type::find($id);
    }
    public function getNameType($name){
        return Type::where('name',$name)->first();
    }
    public function insertType($data){
        return Type::create($data);
    }
    public function updateType($data, $id){
        $Type=Type::find($id);
        $Type->name=$data['name'];
        $Type->save();
    }
    public function deleteType($id){

        $Type=Type::find($id);
        if(!empty($Type)){
            $Type->delete();
        }
    }

}
