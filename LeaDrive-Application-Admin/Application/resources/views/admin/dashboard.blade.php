@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-dark-card rounded-xl p-6 border border-dark-border relative overflow-hidden group hover:border-primary-500/50 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-16 h-16 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
            </svg>
        </div>
        <h3 class="text-dark-muted text-sm font-medium uppercase tracking-wider mb-2">Total Users</h3>
        <div class="text-3xl font-bold text-white mb-1">{{ \App\Models\User::count() }}</div>
        <div class="flex items-center text-sm text-green-400">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            <span>+12% this month</span>
        </div>
    </div>

    <div class="bg-dark-card rounded-xl p-6 border border-dark-border relative overflow-hidden group hover:border-green-500/50 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-16 h-16 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <h3 class="text-dark-muted text-sm font-medium uppercase tracking-wider mb-2">Total Revenue</h3>
        <div class="text-3xl font-bold text-white mb-1">
            Rp {{ number_format(\Illuminate\Support\Facades\DB::table('transactions')->where('status', 'success')->sum('amount'), 0, ',', '.') }}
        </div>
        <div class="flex items-center text-sm text-green-400">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            <span>+8.2% this month</span>
        </div>
    </div>

    <div class="bg-dark-card rounded-xl p-6 border border-dark-border relative overflow-hidden group hover:border-blue-500/50 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-16 h-16 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <h3 class="text-dark-muted text-sm font-medium uppercase tracking-wider mb-2">Transactions</h3>
        <div class="text-3xl font-bold text-white mb-1">{{ \Illuminate\Support\Facades\DB::table('transactions')->count() }}</div>
        <div class="flex items-center text-sm text-blue-400">
            <span>Total processed</span>
        </div>
    </div>

    <div class="bg-dark-card rounded-xl p-6 border border-dark-border relative overflow-hidden group hover:border-yellow-500/50 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-16 h-16 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <h3 class="text-dark-muted text-sm font-medium uppercase tracking-wider mb-2">Pending</h3>
        <div class="text-3xl font-bold text-white mb-1">{{ \Illuminate\Support\Facades\DB::table('transactions')->where('status', 'pending')->count() }}</div>
        <div class="flex items-center text-sm text-yellow-400">
            <span>Needs attention</span>
        </div>
    </div>
</div>

<!-- Charts & Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Chart -->
    <div class="lg:col-span-2 bg-dark-card rounded-xl border border-dark-border p-6">
        <h3 class="text-lg font-semibold text-white mb-6">Revenue Overview</h3>
        <div class="relative h-80 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-dark-card rounded-xl border border-dark-border p-6">
        <h3 class="text-lg font-semibold text-white mb-6">Recent Activity</h3>
        <div class="space-y-6">
            @foreach(\Illuminate\Support\Facades\DB::table('transactions')->join('users', 'transactions.user_id', '=', 'users.id')->select('transactions.*', 'users.name')->orderBy('created_at', 'desc')->limit(5)->get() as $activity)
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center 
                        {{ $activity->status == 'success' ? 'bg-green-900/30 text-green-500' : 
                           ($activity->status == 'pending' ? 'bg-yellow-900/30 text-yellow-500' : 'bg-red-900/30 text-red-500') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">
                        New transaction from {{ $activity->name }}
                    </p>
                    <p class="text-xs text-dark-muted">
                        Rp {{ number_format($activity->amount, 0, ',', '.') }} • {{ ucfirst($activity->status) }}
                    </p>
                </div>
                <div class="text-xs text-dark-muted whitespace-nowrap">
                    {{ $activity->created_at ? \Carbon\Carbon::parse($activity->created_at)->diffForHumans() : 'N/A' }}
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6 pt-6 border-t border-dark-border">
            <a href="{{ route('admin.transactions.index') }}" class="text-sm text-primary-500 hover:text-primary-400 font-medium flex items-center justify-center">
                View All Activity
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($revenue) !!},
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#2d3748'
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                }
            }
        }
    });
</script>
@endsection
