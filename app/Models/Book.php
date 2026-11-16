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
        'cover_path',
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

    public function getFormattedYearAttribute(): string
    {
        if ($this->year === null || (int) $this->year === 0) {
            return 'Sin fecha';
        }

        $year = (int) $this->year;

        return abs($year) . (
            $year < 0 ? ' a. C.' : ' d. C.'
        );
    }
}