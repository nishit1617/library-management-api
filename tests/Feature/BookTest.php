<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_book() {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/books', [
            'title' => 'New Book',
            'author' => 'Author Name',
            'description' => 'Test description',
            'is_borrowed' => false
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', [
            'title' => 'New Book',
            'author' => 'Author Name'
        ]);
    }

    public function test_user_cannot_create_book() {
        $user = User::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/books', [
            'title' => 'Fail Book',
            'author' => 'Author Name'
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_book() {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->putJson("/api/books/{$book->id}", [
            'title' => 'Updated Book',
            'author' => 'New Author',
            'is_borrowed' => false
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['title' => 'Updated Book']);
    }

    public function test_admin_can_delete_book() {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/books/{$book->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
