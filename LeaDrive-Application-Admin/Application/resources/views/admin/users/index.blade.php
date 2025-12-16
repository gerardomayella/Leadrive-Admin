@extends('layouts.admin')

@section('header', 'User Management')

@section('content')
<div class="bg-dark-card rounded-xl border border-dark-border overflow-hidden">
    <div class="p-6 border-b border-dark-border">
        <h3 class="text-lg font-semibold text-white">All Users</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-dark-bg text-dark-muted text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">User</th>
                    <th class="px-6 py-4 font-semibold">Role</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Joined Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-border">
                @foreach($users as $user)
                <tr class="hover:bg-dark-bg/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-primary-500/20 text-primary-500 flex items-center justify-center font-bold text-lg mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-white">{{ $user->name }}</div>
                                <div class="text-sm text-dark-muted">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->role === 'admin' ? 'bg-purple-900/30 text-purple-400 border border-purple-500/30' : 'bg-blue-900/30 text-blue-400 border border-blue-500/30' }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->status === 'active' ? 'bg-green-900/30 text-green-400 border border-green-500/30' : 
                               ($user->status === 'pending' ? 'bg-yellow-900/30 text-yellow-400 border border-yellow-500/30' : 'bg-red-900/30 text-red-400 border border-red-500/30') }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 
                                {{ $user->status === 'active' ? 'bg-green-400' : 
                                   ($user->status === 'pending' ? 'bg-yellow-400' : 'bg-red-400') }}"></span>
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-muted">
                        {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-dark-border">
        {{ $users->links() }}
    </div>
</div>
@endsection
