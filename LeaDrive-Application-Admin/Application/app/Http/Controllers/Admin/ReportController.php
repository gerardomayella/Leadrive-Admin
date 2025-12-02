<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Statistics
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)->count();
        
        // Transaction Stats
        $totalTransactions = DB::table('transactions')->count();
        $totalRevenue = DB::table('transactions')->where('status', 'success')->sum('amount');
        $pendingTransactions = DB::table('transactions')->where('status', 'pending')->count();

        // Recent Transactions
        $recentTransactions = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.name as user_name')
            ->orderBy('transactions.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'totalUsers', 
            'newUsersThisMonth', 
            'totalTransactions', 
            'totalRevenue', 
            'pendingTransactions',
            'recentTransactions'
        ));
    }
}
