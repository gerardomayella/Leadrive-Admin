@extends('layouts.admin')

@section('header', 'Reports & Statistics')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-dark-card rounded-xl p-6 border border-dark-border">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-blue-900/30 p-3 rounded-lg text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <span class="text-sm font-medium text-green-400 bg-green-900/20 px-2 py-1 rounded">+{{ $newUsersThisMonth }} new</span>
        </div>
        <h3 class="text-dark-muted text-sm font-medium uppercase tracking-wider mb-1">Total Users</h3>
        <div class="text-2xl font-bold text-white">{{ $totalUsers }}</div>
    </div>

    <div class="bg-dark-card rounded-xl p-6 border border-dark-border">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-green-900/30 p-3 rounded-lg text-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <h3 class="text-dark-muted text-sm font-medium uppercase tracking-wider mb-1">Total Revenue</h3>
        <div class="text-2xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>

    <div class="bg-dark-card rounded-xl p-6 border border-dark-border">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-yellow-900/30 p-3 rounded-lg text-yellow-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
        </div>
        <h3 class="text-dark-muted text-sm font-medium uppercase tracking-wider mb-1">Total Transactions</h3>
        <div class="text-2xl font-bold text-white">{{ $totalTransactions }}</div>
    </div>

    <div class="bg-dark-card rounded-xl p-6 border border-dark-border">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-red-900/30 p-3 rounded-lg text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <h3 class="text-dark-muted text-sm font-medium uppercase tracking-wider mb-1">Pending Transactions</h3>
        <div class="text-2xl font-bold text-white">{{ $pendingTransactions }}</div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-dark-card rounded-xl border border-dark-border overflow-hidden">
    <div class="p-6 border-b border-dark-border">
        <h3 class="text-lg font-semibold text-white">Recent Transactions</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-dark-bg text-dark-muted text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">ID</th>
                    <th class="px-6 py-4 font-semibold">User</th>
                    <th class="px-6 py-4 font-semibold">Amount</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-border">
                @foreach($recentTransactions as $transaction)
                <tr class="hover:bg-dark-bg/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-dark-muted">
                        #{{ $transaction->id }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-white">{{ $transaction->user_name }}</div>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
