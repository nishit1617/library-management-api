<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class BorrowingTest extends TestCase
{

   use RefreshDatabase;

   public function test_user_can_borrow_book() {
        $user = User::factory()->create(['role' => 'user']);
        $book = Book::factory()->create(['is_borrowed' => 0]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/books/borrow/{$book->id}");
        $response->assertStatus(200);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => 1
        ]);
    }

    public function test_user_can_return_book() {
        $user = User::factory()->create(['role' => 'user']);
        $book = Book::factory()->create(['is_borrowed' => 1]);

        // Create borrowing record
        $borrowing = \App\Models\Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'returned_at' => null,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/books/return/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Book returned successfully',
                 ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => 0
        ]);

        $this->assertNotNull($borrowing->fresh()->returned_at);
    }
}
