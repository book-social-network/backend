<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\DetailAuthorType;
use App\Repositories\Interfaces\AuthorInterface;
use App\Repositories\Interfaces\CloudInterface;
use App\Repositories\Interfaces\DetailAuthorBookInterface;
use App\Repositories\Interfaces\DetailAuthorTypeInterface;
use App\Repositories\Interfaces\TypeInterface;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    private $author;
    private $type;
    private $detailAuthorType;
    private $detailAuthorBook, $cloud;
    public function __construct(AuthorInterface $authorInterface, TypeInterface $typeInterface,DetailAuthorTypeInterface $detailAuthorTypeInterface, DetailAuthorBookInterface $detailAuthorBookInterface, CloudInterface $cloudInterface){
        $this->detailAuthorBook=$detailAuthorBookInterface;
        $this->author=$authorInterface;
        $this->type=$typeInterface;
        $this->detailAuthorType=$detailAuthorTypeInterface;
        $this->cloud=$cloudInterface;
    }
    public function index(){
        $authors=$this->author->getAllAuthors();
        return response()->json($authors);
    }
    public function getAuthor($id){
        $author=$this->author->getAuthor($id);
        return response()->json($author);
    }
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $cloudinaryImage = null;

        // Xử lý hình ảnh nếu có
        if ($request->hasFile('image')) {
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'),'author');
        }

        // Tạo dữ liệu sách
        $authorData = array_merge($request->all(), ['image' => $cloudinaryImage]);

        try {
            // Chèn vào cơ sở dữ liệu
            $authorData = $this->author->insertAuthor($authorData);
            return response()->json($authorData, 201); // Trả về mã trạng thái 201 để chỉ ra rằng tài nguyên đã được tạo
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to insert book.'], 500);
        }
    }
    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string',
        ]);
        $author=$this->author->getAuthor($id);
        if(!$author){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        $cloudinaryImage = $author->image;
        if ($request->hasFile('image')) {
            if ($author->image) {
                $this->cloud->deleteCloud($author->image);
            }
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'),'avatar');
        }
        $this->author->updateAuthor(array_merge(
            $request->all(),
            [
                'password' => bcrypt($request->password),
                'image' => $cloudinaryImage,
            ]
        ), $author->id);
        return response()->json(['message' => 'Update author successful']);
    }
    public function delete($id){
        $author=$this->author->getAuthor($id);
        if(!$author){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        $this->cloud->deleteCloud($author->image);
        $this->author->deleteAuthor($id);
        return response()->json(['message' => 'Delete author successful']);
    }
    //Type book
    public function insertTypeBookForAuthor(Request $request){
        $request->validate([
            'type_id' => 'required|integer',
            'author_id' => 'required|integer'
        ]);
        $type=$this->type->getType($request->get('type_id'));
        $author=$this->author->getAuthor($request->get('author_id'));
        if(!$author || !$type){
            return response()->json(['message' => 'Not found author or type book'], 404);
        }
        $this->detailAuthorType->insertDetailAuthorType([
            'author_id' => $author->id,
            'type_id' => $type->id
        ]);
        return response()->json(['message' => 'Insert type for author successful']);
    }
    public function deleteTypeBookForAuthor($id){
        $detail=$this->detailAuthorType->getDetailAuthorType($id);
        if(!$detail){
            return response()->json(['message' => 'Not found type book of author'], 404);
        }
        $this->detailAuthorType->deleteDetailAuthorType($detail->id);
        return response()->json(['message' => 'Insert type for author successful']);
    }
    public function getAllTypeOfAuthor($idAuthor){
        $types=$this->detailAuthorType->getAllTypeWithAuthor($idAuthor);
        if(!$types){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json($types);
    }
    //Book
    public function getAllBookOfAuthor($idAuthor){
        $book=$this->detailAuthorBook->getAllBookOfAuthor($idAuthor);
        if(!$book){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json($book);
    }
}
