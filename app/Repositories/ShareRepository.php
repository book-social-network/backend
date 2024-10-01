<?php

namespace App\Repositories;

use App\Models\Share;
use App\Repositories\Interfaces\ShareInterface;

class ShareRepository implements ShareInterface{
    public function getAllShareOfPost($idPost){
        return Share::where('post_id', $idPost)->get();

    }
    public function getAllShareOfUser($idUser){
        return Share::where('user_id', $idUser)->get();
    }
    public function insertShare($data){
        Share::create($data);
    }
    public function deleteShare($id){
        $Share=Share::find($id);
        if(!empty($Share)){
            $Share->delete();
        }
    }

}
