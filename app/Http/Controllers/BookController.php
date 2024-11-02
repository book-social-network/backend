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
use App\Repositories\Interfaces\DetailPostBookInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\TypeInterface;
use Cloudinary;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $book, $type, $author, $detailAuthorBook, $detailBookType, $assessment, $detailPostBook;
    public function __construct(BookInterface $bookInterface, TypeInterface $typeInterface, AuthorInterface $authorInterface, DetailAuthorBookInterface $detailAuthorBookInterface, DetailBookTypeInterface $detailBookTypeInterface, AssessmentInterface $assessmentInterface, DetailPostBookInterface $detailPostBookInterface)
    {
        $this->book = $bookInterface;
        $this->type = $typeInterface;
        $this->author = $authorInterface;
        $this->detailAuthorBook = $detailAuthorBookInterface;
        $this->detailBookType = $detailBookTypeInterface;
        $this->assessment = $assessmentInterface;
        $this->detailPostBook = $detailPostBookInterface;
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
        $assessment = $this->assessment->getAssessmentWithIdBookAndUser($book->id, auth()->user()->id);
        return response()->json([$book, $assessment || null]);
    }
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_book' => 'required' // Kiểm tra rằng link_book là một URL hợp lệ
        ]);

        $cloudinaryImage = null;

        // Xử lý hình ảnh nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->getRealPath();
            $uploadResponse = Cloudinary::upload($imagePath, [
                'folder' => 'book'
            ]);
            $cloudinaryImage = $uploadResponse->getSecurePath();
        }

        // Tạo dữ liệu sách
        $bookData = array_merge($request->all(), ['image' => $cloudinaryImage]);

        try {
            // Chèn vào cơ sở dữ liệu
            $book = $this->book->insertBook($bookData);
            return response()->json($book, 201); // Trả về mã trạng thái 201 để chỉ ra rằng tài nguyên đã được tạo
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to insert book.'], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required',
            'link_book' => 'required'
        ]);
        $book = $this->book->getBook($id);
        if (!$book) {
            return response()->json(['message' => 'Not found book with id'], 404);
        }
        $this->book->updateBook($request->all(), $book->id);
        return response()->json(['message' => 'Update book successful']);
    }
    public function delete($id)
    {
        $book = $this->book->getBook($id);
        if (!$book) {
            return response()->json(['message' => 'Not found book with id'], 404);
        }
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
        return response()->json(['message' => 'Insert type for Book successful']);
    }
    public function getAllTypeOfAuthor($idBook)
    {
        $types = $this->detailBookType->getAllTypeOfBook($idBook);
        if (!$types) {
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json($types);
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
    public function getAllAuthorForBook($idBook)
    {
        $authors = $this->detailAuthorBook->getAllAuthorOfBook($idBook);
        if (!$authors) {
            return response()->json(['message' => 'Not found author with id'], 404);
        }
        return response()->json($authors);
    }
    // Post
    public function getAllPostOfBook($idBook)
    {
        $posts = $this->detailPostBook->getAllPostOfBook($idBook);
        if (!$posts) {
            return response()->json(['message' => 'Not found post with id'], 404);
        }
        return response()->json($posts);
    }
}
