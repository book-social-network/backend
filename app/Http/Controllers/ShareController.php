<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ShareInterface;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    private $share;
    public function __construct(ShareInterface $shareInterface){
        $this->share=$shareInterface;
    }
    public function index(){
        $shares=$this->share->getAllShare();
        if(empty($shares)){
            return response()->json(['message' => 'Not found share'], 404);
        }
        return response()->json($shares);
    }
    public function getShare($id){
        $share=$this->share->getShare($id);
        if(empty($share)){
            return response()->json(['message' => 'Not found share with id'], 404);
        }
        return response()->json($share);
    }
    public function insert(Request $request){
        $request->validate([
            'post_id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);
        $this->share->insertShare($request->all());
        return response()->json(['message'=> 'Create url share book successful']);
    }
    public function delete($id){
        $share=$this->share->getShare($id);
        if(empty($share)){
            return response()->json(['message'=> 'Not found url share book with id'],404);
        }
        $this->share->deleteShare($id);
        return response()->json(['message'=> 'Delete url share book in post successful']);
    }
    public function getAllShareOfUser($idUser){
        $shares=$this->share->getAllShareOfUser($idUser);
        if(empty($shares)){
            return response()->json(['message'=> 'Not found url share book with id user'],404);
        }
        return response()->json($shares);
    }
    public function getAllShareOfBook($idBook){
        $shares=$this->share->getAllShareOfBook($idBook);
        if(empty($shares)){
            return response()->json(['message'=> 'Not found url share book with id book'],404);
        }
        return response()->json($shares);
    }
}
