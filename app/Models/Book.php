<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Borrowing;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title','author','description','is_borrowed'];

    protected $casts = [
    'is_borrowed' => 'boolean',
    ];

    public function borrowings() {
        return $this->hasMany(Borrowing::class);
    }
}
