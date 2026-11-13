<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'year',
        'genre',
        'quantity',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function activeLoansCount(): int
    {
        return $this->transactions()->whereNull('date_of_return')->count();
    }

    public function availableCopies(): int
    {
        return max(0, $this->quantity - $this->activeLoansCount());
    }
}