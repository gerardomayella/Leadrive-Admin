<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $kursus->nama_kursus }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #0f1419;
            color: #fff;
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #1a2332;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #2d3748;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .logo-text h2 {
            color: #fff;
            font-size: 1.25rem;
            margin-bottom: 0.125rem;
        }

        .logo-text p {
            color: #8b92a0;
            font-size: 0.75rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-section-title {
            padding: 0.5rem 1.5rem;
            color: #6b7280;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-top: 1rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: #8b92a0;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(255, 107, 53, 0.1);
            color: #ff6b35;
            border-left-color: #ff6b35;
        }

        .menu-item-icon {
            width: 24px;
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .menu-item-text {
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: #1a2332;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #2d3748;
        }

        .header-left h1 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .header-left p {
            color: #8b92a0;
            font-size: 0.875rem;
        }

        .header-right {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
        }

        .btn-logout {
            background: #dc2626;
            color: white;
        }

        .btn-logout:hover {
            background: #b91c1c;
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

        /* Content Container */
        .content-container {
            padding: 2rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #1a2332;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #2d3748;
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            border-color: #ff6b35;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.orange { background: rgba(255, 107, 53, 0.15); }
        .stat-icon.blue { background: rgba(59, 130, 246, 0.15); }
        .stat-icon.green { background: rgba(16, 185, 129, 0.15); }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #8b92a0;
            font-size: 0.875rem;
        }

        /* Section */
        .section {
            background: #1a2332;
            border-radius: 12px;
            border: 1px solid #2d3748;
            margin-bottom: 2rem;
        }

        .section-header {
            padding: 1.5rem;
            border-bottom: 1px solid #2d3748;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 1.125rem;
            color: #fff;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-content {
            padding: 1.5rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .info-item {
            padding: 1rem;
            background: #0f1419;
            border-radius: 8px;
            border: 1px solid #2d3748;
        }

        .info-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .info-value {
            font-size: 0.95rem;
            color: #fff;
            font-weight: 500;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #0f1419;
        }

        th {
            text-align: left;
            padding: 1rem;
            color: #8b92a0;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1rem;
            border-top: 1px solid #2d3748;
            color: #fff;
            font-size: 0.875rem;
        }

        tbody tr {
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #0f1419;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #8b92a0;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #fff;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            margin-bottom: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                flex-direction: column;
                gap: 1rem;
            }

            .header-right {
                width: 100%;
                justify-content: space-between;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card, .section {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">🏫</div>
                <div class="logo-text">
                    <h2>LeadDrive</h2>
                    <p>Kursus Dashboard</p>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-menu">
            <div class="menu-section-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="menu-item active">
                <span class="menu-item-icon">🏠</span>
                <span class="menu-item-text">Dashboard</span>
            </a>
            <a href="#" class="menu-item">
                <span class="menu-item-icon">👨‍🏫</span>
                <span class="menu-item-text">Instruktur</span>
            </a>
            <a href="#" class="menu-item">
                <span class="menu-item-icon">👥</span>
                <span class="menu-item-text">Siswa</span>
            </a>
            <a href="#" class="menu-item">
                <span class="menu-item-icon">📅</span>
                <span class="menu-item-text">Jadwal</span>
            </a>
            
            <div class="menu-section-title">Pengaturan</div>
            <a href="#" class="menu-item">
                <span class="menu-item-icon">⚙️</span>
                <span class="menu-item-text">Profil Kursus</span>
            </a>
            <a href="#" class="menu-item">
                <span class="menu-item-icon">📄</span>
                <span class="menu-item-text">Dokumen</span>
            </a>
            
            <div class="menu-section-title">Akun</div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="menu-item" style="width: 100%; background: none; text-align: left;">
                    <span class="menu-item-icon">🚪</span>
                    <span class="menu-item-text">Keluar</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <h1>🏫 {{ $kursus->nama_kursus }}</h1>
                <p>Selamat datang kembali! Kelola kursus Anda dengan mudah.</p>
            </div>
            <div class="header-right">
                <div class="user-badge">
                    <div class="user-avatar">{{ strtoupper(substr($kursus->nama_kursus, 0, 1)) }}</div>
                    <span>{{ $kursus->email }}</span>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-container">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">{{ $stats['total_instruktur'] }}</div>
                            <div class="stat-label">Total Instruktur</div>
                        </div>
                        <div class="stat-icon orange">👨‍🏫</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">{{ $stats['instruktur_aktif'] }}</div>
                            <div class="stat-label">Instruktur Aktif</div>
                        </div>
                        <div class="stat-icon green">✅</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">{{ $stats['total_sertifikat'] }}</div>
                            <div class="stat-label">Bersertifikat</div>
                        </div>
                        <div class="stat-icon blue">🎓</div>
                    </div>
                </div>
            </div>

            <!-- Informasi Kursus -->
            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        📋 Informasi Kursus
                    </div>
                    <a href="#" class="btn btn-primary">
                        ✏️ Edit Profil
                    </a>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $kursus->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nomor HP</div>
                            <div class="info-value">{{ $kursus->nomor_hp ?? 'Belum diisi' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Lokasi</div>
                            <div class="info-value">{{ $kursus->lokasi }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Jam Operasional</div>
                            <div class="info-value">{{ $kursus->jam_buka }} - {{ $kursus->jam_tutup }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instruktur Terbaru -->
            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        👨‍🏫 Instruktur Terbaru
                    </div>
                    <a href="#" class="btn btn-primary">
                        ➕ Tambah Instruktur
                    </a>
                </div>
                <div class="section-content">
                    @if($latestInstrukturs->count() > 0)
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No. HP</th>
                                        <th>Tanggal Bergabung</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestInstrukturs as $instruktur)
                                    <tr>
                                        <td>{{ $instruktur->nama ?? 'N/A' }}</td>
                                        <td>{{ $instruktur->email ?? '-' }}</td>
                                        <td>{{ $instruktur->no_hp ?? '-' }}</td>
                                        <td>
                                            @if(isset($instruktur->tanggal_bergabung) && $instruktur->tanggal_bergabung)
                                                {{ date('d M Y', strtotime($instruktur->tanggal_bergabung)) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($instruktur->status_aktif)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">👤</div>
                            <h3>Belum Ada Instruktur</h3>
                            <p>Tambahkan instruktur pertama Anda untuk memulai mengelola kursus</p>
                            <a href="#" class="btn btn-primary">➕ Tambah Instruktur Pertama</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>
