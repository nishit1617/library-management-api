<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::insert([
            [
                'title'       => 'Clean Code',
                'author'      => 'Robert C. Martin',
                'description' => 'A Handbook of Agile Software Craftsmanship',
                'is_borrowed' => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'The Pragmatic Programmer',
                'author'      => 'Andrew Hunt, David Thomas',
                'description' => 'Your Journey to Mastery',
                'is_borrowed' => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Design Patterns',
                'author'      => 'Erich Gamma et al.',
                'description' => 'Elements of Reusable Object-Oriented Software',
                'is_borrowed' => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
