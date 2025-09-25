<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Book;

class BookController extends Controller
{

     /**
     * @OA\Get(
     *     path="/api/books",
     *     summary="all books",
     *     tags={"CRUD Books"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="author", type="string"),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="is_borrowed", type="boolean")
     *             )
     *         )
     *     )
     * )
     */
    public function index() {
        return Cache::remember('books', 240, function () {
            return Book::paginate(10);
        });
    }

    /**
     * @OA\Post(
     *     path="/api/books",
     *     summary="Store a new book",
     *     tags={"CRUD Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title","author","is_borrowed"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="is_borrowed", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="is_borrowed", type="boolean")
     *         )
     *     )
     * )
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_borrowed' => 'required|boolean',
        ]);

        $validated['is_borrowed'] = $request->boolean('is_borrowed');

        $book = Book::create($validated);
        Cache::forget('books');

        return response()->json($book, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/books/{id}",
     *     summary="Get book by ID",
     *     tags={"CRUD Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="is_borrowed", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */    
    public function show(string $id) {
        $book = Book::find($id);
        if (!$book) {
            $response = "Book Not Found";
            $code = 404;
        } else {
            $response = $book;
            $code = 200;
        } 
        return response()->json($response, $code);
    }

     /**
     * @OA\Put(
     *     path="/api/books/{id}",
     *     summary="Update a book",
     *     tags={"CRUD Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="is_borrowed", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="is_borrowed", type="boolean")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id) {
        $book = Book::find($id);
        $book->update($request->only(['title','author','description','is_borrowed']));
        Cache::forget('books');

        return response()->json($book);
    }

     /**
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     summary="Delete a book",
     *     tags={"CRUD Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Book deleted"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
    public function destroy(string $id) {   
        $book = Book::find($id);
        if ($book) {
            $book->delete();
            Cache::forget('books');
            $message = "Book Deleted Successfully";
            $code = 200;
        } else {
            $message = "Book Not Found";
            $code = 404;
        }

        return response()->json($message, $code);
    }
}
