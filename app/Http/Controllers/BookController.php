<?php

namespace App\Http\Controllers;

use App\Models\DetailAuthorBook;
use App\Models\DetailBookBook;
use App\Repositories\Interfaces\AssessmentInterface;
use App\Repositories\Interfaces\AuthorInterface;
use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\DetailAuthorBookInterface;
use App\Repositories\Interfaces\DetailBookBookInterface;
use App\Repositories\Interfaces\DetailBookTypeInterface;
use App\Repositories\Interfaces\TypeInterface;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $book, $type, $author, $detailAuthorBook, $detailBookType, $assessment;
    public function __construct(BookInterface $bookInterface, TypeInterface $typeInterface, AuthorInterface $authorInterface, DetailAuthorBookInterface $detailAuthorBookInterface, DetailBookTypeInterface $detailBookTypeInterface, AssessmentInterface $assessmentInterface){
        $this->book=$bookInterface;
        $this->type=$typeInterface;
        $this->author=$authorInterface;
        $this->detailAuthorBook=$detailAuthorBookInterface;
        $this->detailBookType=$detailBookTypeInterface;
        $this->assessment=$assessmentInterface;
    }
    public function index(){
        $books=$this->book->getAllBooks();
        return response()->json($books);
    }
    public function getBook($id){
        $book=$this->book->getBook($id);
        if(!$book){
            return response()->json(['message'=> 'Not found book with id'],404);
        }
        $assessment=$this->assessment->getAssessmentWithIdBookAndUser($book->id, auth()->user()->id);
        return response()->json([$book, $assessment || null]);
    }
    public function insert(Request $request){
        $request->validate([
            'name' => 'required|string',
            'image'=> 'required',
            'link_book' =>'required'
        ]);
        $book=$this->book->insertBook($request->all());
        return response()->json($book);
    }
    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string',
            'image'=> 'required',
            'link_book' =>'required'
        ]);
        $book=$this->book->getBook($id);
        if(!$book){
            return response()->json(['message'=> 'Not found book with id'],404);
        }
        $this->book->updateBook($request->all(),$book->id);
        return response()->json(['message' => 'Update book successful']);
    }
    public function delete($id){
        $book=$this->book->getBook($id);
        if(!$book){
            return response()->json(['message' => 'Not found book with id'], 404);
        }
        $this->book->deleteBook($id);
        return response()->json(['message' => 'Delete book successful']);
    }
    //Type
    public function insertTypeBookForBook(Request $request){
        $request->validate([
            'type_id' => 'required|integer',
            'book_id' => 'required|integer'
        ]);
        $type=$this->type->getType($request->get('type_id'));
        $book=$this->book->getBook($request->get('book_id'));
        if(!$book || !$type){
            return response()->json(['message' => 'Not found book or type book'], 404);
        }
        $this->detailBookType->insertDetailBookType([
            'book_id' => $book->id,
            'type_id' => $type->id
        ]);
        return response()->json(['message' => 'Insert type for book successful']);
    }
    public function deleteTypeBookForBook($id){
        $detail=$this->detailBookType->getDetailBookType($id);
        if(!$detail){
            return response()->json(['message' => 'Not found type book of book'], 404);
        }
        $this->detailBookType->deleteDetailBookType($detail->id);
        return response()->json(['message' => 'Insert type for Book successful']);
    }
    public function getAllTypeOfAuthor($idBook){
        $types=$this->detailBookType->getAllTypeOfBook($idBook);
        if(!$types){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json($types);
    }
    //Author
    public function insertAuthorForBook(Request $request){
        $request->validate([
            'author_id' => 'required|integer',
            'book_id' => 'required|integer'
        ]);
        $author=$this->author->getauthor($request->get('author_id'));
        $book=$this->book->getBook($request->get('book_id'));
        if(!$book || !$author){
            return response()->json(['message' => 'Not found book or author book'], 404);
        }
        $this->detailAuthorBook->insertDetailAuthorBook([
            'book_id' => $book->id,
            'author_id' => $author->id
        ]);
        return response()->json(['message' => 'Insert author for book successful']);
    }
    public function deleteAuthorForBook($id){
        $detail=$this->detailAuthorBook->getDetailAuthorBook($id);
        if(!$detail){
            return response()->json(['message' => 'Not found author of book'], 404);
        }
        $this->detailBookType->deleteDetailBookType($detail->id);
        return response()->json(['message' => 'Delete author for Book successful']);
    }
    public function getAllAuthorForBook($idBook){
        $authors=$this->detailAuthorBook->getAllAuthorOfBook($idBook);
        if(!$authors){
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json($authors);
    }
}
