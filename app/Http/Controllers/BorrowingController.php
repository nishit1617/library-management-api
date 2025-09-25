<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Borrowing;
use App\Models\Book;

class BorrowingController extends Controller
{   
    /**
     * @OA\Post(
     *     path="/api/books/borrow/{id}",
     *     summary="Borrow a book",
     *     tags={"Book Borrow"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book borrowed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="book_id", type="integer"),
     *             @OA\Property(property="borrowed_at", type="string", format="date-time"),
     *             @OA\Property(property="returned_at", type="string", format="date-time", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=409, description="Book is already borrowed")
     * )
     */
    public function borrow(Request $request, Book $book)
    {
        $user = $request->user();

        if ($book->is_borrowed) {
            return response()->json(['message' => 'Book is already borrowed'], 409);
        }

        $borrowing = Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        $book->is_borrowed = true;
        $book->save();

        Cache::forget('books');
        event(new \App\Events\BookBorrowed($book, $request->user()));

        return response()->json($borrowing, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/books/return/{id}",
     *     summary="Return a borrowed book",
     *     tags={"Book Return"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book returned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="book_id", type="integer"),
     *             @OA\Property(property="borrowed_at", type="string", format="date-time"),
     *             @OA\Property(property="returned_at", type="string", format="date-time", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="No active borrowing found")
     * )
     */
    public function return(Request $request, Book $book)
    {
        $user = $request->user();

        $borrowing = Borrowing::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereNull('returned_at')
            ->latest()
            ->first();

        if (! $borrowing) {
            return response()->json(['message' => 'No active borrowing found'], 404);
        }

        $borrowing->update(['returned_at' => now()]);

        $book->is_borrowed = false;
        $book->save();

        Cache::forget('books');

        return response()->json($borrowing);
    }
}
