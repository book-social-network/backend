<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DetailAuthorTypeInterface;
use App\Repositories\Interfaces\DetailBookTypeInterface;
use App\Repositories\Interfaces\TypeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TypeController extends Controller
{
    private $type;
    private $detailAuthorType;
    private $detailBookType;
    public function __construct(TypeInterface $typeInterface, DetailBookTypeInterface $detailBookTypeInterface, DetailAuthorTypeInterface $detailAuthorTypeInterface)
    {
        $this->type = $typeInterface;
        $this->detailBookType = $detailBookTypeInterface;
        $this->detailAuthorType = $detailAuthorTypeInterface;
    }
    public function index()
    {
        $types = $this->type->getAllType();
        return response()->json($types);
    }
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:types|string|max:255'
        ]);
        $type = $this->type->insertType($request->all());
        return response()->json($type);
    }

    public function update(Request $request, $id)
    {
        $type = $this->type->getType($id);
        if (!$type) {
            return response()->json(['message' => 'Not found type with id'], 404);
        }
        $this->type->updateType($request->all(), $id);
        return response()->json(['message' => 'Type is updated']);
    }
    public function delete($id)
    {
        $type = $this->type->getType($id);
        if (!$type) {
            return response()->json(['message' => 'Not found type with id'], 404);
        }

        $this->type->deleteType($id);
        return response()->json(['message' => 'Type is deleted']);
    }
    // author
    public function getAllAuthorOfType($idType)
    {
        $type = $this->type->getType($idType);
        if (!$type) {
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json([
            'type' => $type,
            'authors' => $type->author()
        ]);
    }
    //book
    public function getAllBookOfType($idType)
    {
        $type = $this->type->getType($idType);

        if (!$type) {
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json([
            'type' => $type,
            'books' => $type->book()
        ]);
    }
}
