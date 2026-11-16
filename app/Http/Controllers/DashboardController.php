<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Panel del administrador
    public function administrator()
    {
        $booksCount = Book::count();

        $usersCount = User::count();

        $activeLoansCount = Transaction::query()
            ->whereNull('date_of_return')
            ->count();

        $returnedLoansCount = Transaction::query()
            ->whereNotNull('date_of_return')
            ->count();

        return view('dashboards.administrator', compact(
            'booksCount',
            'usersCount',
            'activeLoansCount',
            'returnedLoansCount'
        ));
    }

    // Panel del bibliotecario
    public function librarian()
    {
        $booksCount = Book::count();

        $totalCopies = (int) Book::sum('quantity');

        $activeLoansCount = Transaction::query()
            ->whereNull('date_of_return')
            ->count();

        $availableCopies = max(
            0,
            $totalCopies - $activeLoansCount
        );

        return view('dashboards.librarian', compact(
            'booksCount',
            'availableCopies',
            'activeLoansCount'
        ));
    }

    // Panel del lector
    public function reader()
    {
        $userId = Auth::id();

        $myActiveLoans = Transaction::query()
            ->where('user_id', $userId)
            ->whereNull('date_of_return')
            ->count();

        $myReturnedLoans = Transaction::query()
            ->where('user_id', $userId)
            ->whereNotNull('date_of_return')
            ->count();

        $booksCount = Book::count();

        return view('dashboards.reader', compact(
            'myActiveLoans',
            'myReturnedLoans',
            'booksCount'
        ));
    }
}
