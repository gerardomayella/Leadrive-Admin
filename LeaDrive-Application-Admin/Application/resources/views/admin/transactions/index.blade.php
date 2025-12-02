@extends('layouts.admin')

@section('header', 'Transactions')

@section('content')
<div class="bg-dark-card rounded-xl border border-dark-border overflow-hidden">
    <div class="p-6 border-b border-dark-border">
        <h3 class="text-lg font-semibold text-white">All Transactions</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-dark-bg text-dark-muted text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">ID</th>
                    <th class="px-6 py-4 font-semibold">User</th>
                    <th class="px-6 py-4 font-semibold">Package</th>
                    <th class="px-6 py-4 font-semibold">Amount</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Date</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-border">
                @foreach($transactions as $transaction)
                <tr class="hover:bg-dark-bg/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-dark-muted">
                        #{{ $transaction->id }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-white">{{ $transaction->user_name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-300">
                        {{ $transaction->nama_paket ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-white">
                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $transaction->status === 'success' ? 'bg-green-900/30 text-green-400 border border-green-500/30' : 
                               ($transaction->status === 'pending' ? 'bg-yellow-900/30 text-yellow-400 border border-yellow-500/30' : 'bg-red-900/30 text-red-400 border border-red-500/30') }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-muted">
                        {{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('d M Y H:i') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="text-primary-500 hover:text-primary-400 font-medium text-sm transition-colors">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-dark-border">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
