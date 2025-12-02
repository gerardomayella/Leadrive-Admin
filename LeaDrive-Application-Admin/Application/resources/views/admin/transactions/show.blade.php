@extends('layouts.admin')

@section('header', 'Transaction Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-dark-card rounded-xl border border-dark-border overflow-hidden">
        <div class="p-6 border-b border-dark-border flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white">Transaction #{{ $transaction->id }}</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                {{ $transaction->status === 'success' ? 'bg-green-900/30 text-green-400 border border-green-500/30' : 
                   ($transaction->status === 'pending' ? 'bg-yellow-900/30 text-yellow-400 border border-yellow-500/30' : 'bg-red-900/30 text-red-400 border border-red-500/30') }}">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>
        
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-dark-muted mb-1">User Name</label>
                    <div class="text-white font-medium">{{ $transaction->user_name }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-muted mb-1">User Email</label>
                    <div class="text-white font-medium">{{ $transaction->user_email }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-muted mb-1">Package</label>
                    <div class="text-white font-medium">{{ $transaction->nama_paket ?? 'N/A' }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-muted mb-1">Amount</label>
                    <div class="text-xl font-bold text-white">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-muted mb-1">Payment Method</label>
                    <div class="text-white font-medium">{{ $transaction->payment_method ?? 'N/A' }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-muted mb-1">Date</label>
                    <div class="text-white font-medium">{{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('d M Y H:i') : 'N/A' }}</div>
                </div>
            </div>

            @if($transaction->payment_proof)
            <div class="border-t border-dark-border pt-6">
                <label class="block text-sm font-medium text-dark-muted mb-2">Payment Proof</label>
                <div class="bg-dark-bg p-4 rounded-lg border border-dark-border">
                    <img src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Payment Proof" class="max-w-full h-auto rounded">
                </div>
            </div>
            @endif

            <div class="border-t border-dark-border pt-6">
                <label class="block text-sm font-medium text-dark-muted mb-2">Update Status</label>
                <form action="{{ route('admin.transactions.updateStatus', $transaction->id) }}" method="POST" class="flex items-center gap-4">
                    @csrf
                    <select name="status" class="bg-dark-bg border border-dark-border text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="success" {{ $transaction->status == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none transition-colors">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
