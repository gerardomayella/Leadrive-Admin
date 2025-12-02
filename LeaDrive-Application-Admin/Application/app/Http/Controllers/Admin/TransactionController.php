<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        // Assuming 'transactions' table exists and has relationships
        // Since we don't have a Transaction model yet, we might use DB facade or create a model
        // Let's create a model first or use DB for now.
        // Ideally we should have a Transaction model.
        
        $transactions = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->leftJoin('paket_kursus', 'transactions.paket_kursus_id', '=', 'paket_kursus.id_paket')
            ->select('transactions.*', 'users.name as user_name', 'paket_kursus.nama_paket')
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->leftJoin('paket_kursus', 'transactions.paket_kursus_id', '=', 'paket_kursus.id_paket')
            ->select('transactions.*', 'users.name as user_name', 'users.email as user_email', 'paket_kursus.nama_paket')
            ->where('transactions.id', $id)
            ->first();

        if (!$transaction) {
            return redirect()->route('admin.transactions.index')->with('error', 'Transaction not found.');
        }

        return view('admin.transactions.show', compact('transaction'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed,cancelled',
        ]);

        DB::table('transactions')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.transactions.show', $id)->with('success', 'Transaction status updated.');
    }
}
