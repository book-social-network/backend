<?php

namespace App\Http\Controllers;

use App\Models\DetailAuthorBook;
use App\Models\DetailBookBook;
use App\Repositories\Interfaces\AssessmentInterface;
use App\Repositories\Interfaces\AuthorInterface;
use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\CloudInterface;
use App\Repositories\Interfaces\DetailAuthorBookInterface;
use App\Repositories\Interfaces\DetailBookBookInterface;
use App\Repositories\Interfaces\DetailBookTypeInterface;
use App\Repositories\Interfaces\DetailPostBookInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\TypeInterface;
use Cloudinary;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $book, $type, $author, $detailAuthorBook, $detailBookType, $assessment, $detailPostBook, $cloud;
    public function __construct(BookInterface $bookInterface, TypeInterface $typeInterface, AuthorInterface $authorInterface, DetailAuthorBookInterface $detailAuthorBookInterface, DetailBookTypeInterface $detailBookTypeInterface, AssessmentInterface $assessmentInterface, DetailPostBookInterface $detailPostBookInterface, CloudInterface $cloudInterface)
    {
        $this->book = $bookInterface;
        $this->type = $typeInterface;
        $this->author = $authorInterface;
        $this->detailAuthorBook = $detailAuthorBookInterface;
        $this->detailBookType = $detailBookTypeInterface;
        $this->assessment = $assessmentInterface;
        $this->detailPostBook = $detailPostBookInterface;
        $this->cloud=$cloudInterface;
    }
    public function index()
    {
        $books = $this->book->getAllBooks();
        return response()->json($books);
    }
    public function getBook($id)
    {
        $book = $this->book->getBook($id);
        if (!$book) {
            return response()->json(['message' => 'Not found book with id'], 404);
        }
        $types = $this->detailBookType->getAllTypeOfBook($id);
        $authors = $this->detailAuthorBook->getAllAuthorOfBook($id);
        $assessment = $this->assessment->getAssessmentWithIdBookAndUser($book->id, auth()->user()->id);
        return response()->json([
            'book' => $book,
            'types' => $types,
            'authors' => $authors,
            'assessment' => $assessment
        ]);
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
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'),'book');
        }

        // Chèn vào cơ sở dữ liệu
        $book = $this->book->insertBook(array_merge($request->all(), ['image' => $cloudinaryImage]));
        return response()->json($book, 201); // Trả về mã trạng thái 201 để chỉ ra rằng tài nguyên đã được tạo
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $book = $this->book->getBook($id);
        if (!$book) {
            return response()->json(['message' => 'Not found book with id'], 404);
        }

        $cloudinaryImage = $book->image;

        if ($request->hasFile('image')) {
            if ($book->image) {
                $this->cloud->deleteCloud($book->image);
            }
            $cloudinaryImage = $this->cloud->insertCloud($request->file('image'),'book');
        }

        $this->book->updateBook(array_merge(
            $request->all(),
            [
                'image' => $cloudinaryImage,
            ]
        ), $book->id);

        return response()->json(['message' => 'Update book successful']);
    }
    public function delete($id)
    {
        $book = $this->book->getBook($id);
        if (!$book) {
            return response()->json(['message' => 'Not found book with id'], 404);
        }
        $this->cloud->deleteCloud($book->image);
        $this->book->deleteBook($id);
        return response()->json(['message' => 'Delete book successful']);
    }
    //Type
    public function insertTypeBookForBook(Request $request)
    {
        $request->validate([
            'type_id' => 'required|integer',
            'book_id' => 'required|integer'
        ]);
        $type = $this->type->getType($request->get('type_id'));
        $book = $this->book->getBook($request->get('book_id'));
        if (!$book || !$type) {
            return response()->json(['message' => 'Not found book or type book'], 404);
        }
        $this->detailBookType->insertDetailBookType([
            'book_id' => $book->id,
            'type_id' => $type->id
        ]);
        return response()->json(['message' => 'Insert type for book successful']);
    }
    public function deleteTypeBookForBook($id)
    {
        $detail = $this->detailBookType->getDetailBookType($id);
        if (!$detail) {
            return response()->json(['message' => 'Not found type book of book'], 404);
        }
        $this->detailBookType->deleteDetailBookType($detail->id);
        return response()->json(['message' => 'Delete type for Book successful']);
    }
    //Author
    public function insertAuthorForBook(Request $request)
    {
        $request->validate([
            'author_id' => 'required|integer',
            'book_id' => 'required|integer'
        ]);
        $author = $this->author->getauthor($request->get('author_id'));
        $book = $this->book->getBook($request->get('book_id'));
        if (!$book || !$author) {
            return response()->json(['message' => 'Not found book or author book'], 404);
        }
        $this->detailAuthorBook->insertDetailAuthorBook([
            'book_id' => $book->id,
            'author_id' => $author->id
        ]);
        return response()->json(['message' => 'Insert author for book successful']);
    }
    public function deleteAuthorForBook($id)
    {
        $detail = $this->detailAuthorBook->getDetailAuthorBook($id);
        if (!$detail) {
            return response()->json(['message' => 'Not found author of book'], 404);
        }
        $this->detailBookType->deleteDetailBookType($detail->id);
        return response()->json(['message' => 'Delete author for Book successful']);
    }
    // Post
    public function getAllPostOfBook($idBook)
    {
        $book = $this->book->getBook($idBook);
        $posts=$book->post()->get();
        if (!$posts) {
            return response()->json(['message' => 'Not found post with id'], 404);
        }
        return response()->json([
            'book' => $book,
            'posts' => $posts
        ]);
    }
}
