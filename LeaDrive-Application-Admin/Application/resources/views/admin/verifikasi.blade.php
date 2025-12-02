<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kursus - Admin LeadDrive</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #0f1419;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        /* Header */
        .header {
            background: #1a2332;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-left h1 {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .header-left p {
            color: #8b92a0;
            font-size: 0.875rem;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .notification-icon {
            width: 40px;
            height: 40px;
            background: #2d3748;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .notification-icon::before {
            content: '🔔';
            font-size: 1.2rem;
        }
        .user-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #2d3748;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            background: #ff6b35;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .user-badge span {
            color: #fff;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .btn-logout {
            background: #dc2626;
            color: #fff;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn-logout:hover {
            background: #b91c1c;
        }
        
        /* Navigation Tabs */
        .nav-tabs {
            background: #1a2332;
            padding: 0 2rem;
            display: flex;
            gap: 0.5rem;
            border-bottom: 1px solid #2d3748;
        }
        .nav-tab {
            padding: 0.75rem 1.5rem;
            color: #8b92a0;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-tab.active {
            color: #fff;
            background: #ff6b35;
            border-radius: 6px 6px 0 0;
        }
        .nav-tab:not(.active):hover {
            color: #fff;
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }
        .page-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            color: #8b92a0;
            font-size: 0.875rem;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: #1a2332;
            padding: 1.5rem;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
        }
        .stat-header {
            color: #8b92a0;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
            font-weight: 500;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .stat-icon {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-icon.yellow {
            background: rgba(251, 191, 36, 0.15);
        }
        .stat-icon.green {
            background: rgba(16, 185, 129, 0.15);
        }
        .stat-icon.orange {
            background: rgba(255, 107, 53, 0.15);
        }
        .stat-icon.red {
            background: rgba(239, 68, 68, 0.15);
        }
        
        /* Course List Section */
        .section-card {
            background: #1a2332;
            padding: 1.5rem;
            border-radius: 12px;
        }
        .section-header {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #fff;
        }
        
        /* Course Items */
        .course-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .course-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background: #0f1419;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .course-item:hover {
            background: #1a2027;
        }
        .course-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            background: rgba(59, 130, 246, 0.15);
        }
        .course-content {
            flex: 1;
        }
        .course-title {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .course-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .course-instructor,
        .course-duration,
        .course-price {
            color: #8b92a0;
            font-size: 0.875rem;
        }
        .course-instructor::before {
            content: 'Instruktur: ';
        }
        .course-duration::before {
            content: '• ';
        }
        .course-price::before {
            content: '• Rp ';
        }
        .course-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge.pending {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }
        .course-date {
            color: #6b7280;
            font-size: 0.75rem;
        }
        .btn-review {
            background: #ff6b35;
            color: #fff;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-review:hover {
            background: #ff8c5a;
        }
        .btn-review::before {
            content: '👁';
        }
        
        @media (max-width: 768px) {
            .course-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang kembali, Admin Lead Drive</p>
        </div>
        <div class="header-right">
            <div class="notification-icon"></div>
            <div class="user-badge">
                <div class="user-avatar">A</div>
                <span>Admin User</span>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">🚪 Logout</button>
            </form>
        </div>
    </div>
    
    <!-- Navigation Tabs -->
    <div class="nav-tabs">
        <a href="{{ route('admin.dashboard') }}" class="nav-tab">
            🏠 Dashboard
        </a>
        <a href="{{ route('admin.verifikasi') }}" class="nav-tab active">
            ✓ Verifikasi Kursus
        </a>
    </div>
    
    <!-- Main Content -->
    <div class="container">
        @if(session('success'))
            <div style="background: rgba(16, 185, 129, 0.15); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.3);">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Page Header -->
        <div class="page-header">
            <h2>Verifikasi Kursus</h2>
            <p>Review dan verifikasi kursus yang diajukan instruktur</p>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">Menunggu Review</div>
                <div class="stat-value">{{ $counts['pending'] }}</div>
                <div class="stat-icon yellow">⏳</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">Disetujui</div>
                <div class="stat-value">{{ $counts['approved'] }}</div>
                <div class="stat-icon green">✓</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">Ditolak</div>
                <div class="stat-value">{{ $counts['rejected'] }}</div>
                <div class="stat-icon red">✗</div>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.verifikasi', ['status' => 'pending']) }}" 
               class="status-badge {{ $status === 'pending' ? 'pending' : '' }}" 
               style="padding: 0.5rem 1rem; text-decoration: none; {{ $status === 'pending' ? '' : 'background: #2d3748; color: #8b92a0;' }}">
                Pending ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.verifikasi', ['status' => 'approved']) }}" 
               class="status-badge" 
               style="padding: 0.5rem 1rem; text-decoration: none; {{ $status === 'approved' ? 'background: rgba(16, 185, 129, 0.2); color: #10b981;' : 'background: #2d3748; color: #8b92a0;' }}">
                Approved ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('admin.verifikasi', ['status' => 'rejected']) }}" 
               class="status-badge" 
               style="padding: 0.5rem 1rem; text-decoration: none; {{ $status === 'rejected' ? 'background: rgba(239, 68, 68, 0.2); color: #ef4444;' : 'background: #2d3748; color: #8b92a0;' }}">
                Rejected ({{ $counts['rejected'] }})
            </a>
        </div>
        
        <!-- Course List -->
        <div class="section-card">
            <div class="section-header">Kursus {{ ucfirst($status) }}</div>
            
            @if($requests->isEmpty())
                <div style="text-align: center; padding: 3rem; color: #8b92a0;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                    <p>Tidak ada request dengan status {{ $status }}</p>
                </div>
            @else
                <div class="course-list">
                    @foreach($requests as $req)
                        <div class="course-item">
                            <div class="course-icon">🚗</div>
                            <div class="course-content">
                                <div class="course-title">{{ $req->nama_kursus }}</div>
                                <div class="course-meta">
                                    <span class="course-instructor">{{ $req->email }}</span>
                                    <span class="course-duration">{{ $req->nomor_hp }}</span>
                                </div>
                                <div class="course-meta" style="margin-top: 0.5rem;">
                                    <span style="color: #8b92a0; font-size: 0.875rem;">📍 {{ $req->lokasi }}</span>
                                    <span style="color: #8b92a0; font-size: 0.875rem;">• 🕐 {{ $req->jam_buka }} - {{ $req->jam_tutup }}</span>
                                </div>
                                <div class="course-status">
                                    <span class="status-badge {{ $req->status }}" 
                                          style="{{ $req->status === 'approved' ? 'background: rgba(16, 185, 129, 0.2); color: #10b981;' : ($req->status === 'rejected' ? 'background: rgba(239, 68, 68, 0.2); color: #ef4444;' : '') }}">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                    <span class="course-date">
                                        @if($req->waktu && $req->waktu instanceof \Carbon\Carbon)
                                            {{ $req->waktu->diffForHumans() }}
                                        @elseif($req->waktu)
                                            {{ $req->waktu }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('admin.verifikasi.show', $req->id_request) }}" class="btn-review" style="text-decoration: none;">Review</a>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div style="margin-top: 1.5rem;">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
