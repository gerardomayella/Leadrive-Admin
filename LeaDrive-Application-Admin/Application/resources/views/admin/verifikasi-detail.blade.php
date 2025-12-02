<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Verifikasi - Admin LeadDrive</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #0f1419;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .header {
            background: #1a2332;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 1.25rem;
            font-weight: 600;
        }
        .btn-back {
            background: #2d3748;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .btn-back:hover {
            background: #374151;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .detail-card {
            background: #1a2332;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #fff;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .info-label {
            color: #8b92a0;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .info-value {
            color: #fff;
            font-size: 1rem;
        }
        .document-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .document-item {
            background: #0f1419;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }
        .document-item h4 {
            color: #fff;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        .document-preview {
            width: 100%;
            height: 200px;
            background: #2d3748;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .document-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .document-icon {
            font-size: 3rem;
        }
        .btn-view {
            background: #3b82f6;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        .btn-view:hover {
            background: #2563eb;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-approve {
            background: #10b981;
            color: #fff;
        }
        .btn-approve:hover {
            background: #059669;
        }
        .btn-reject {
            background: #ef4444;
            color: #fff;
        }
        .btn-reject:hover {
            background: #dc2626;
        }
        .btn-pending {
            background: #f59e0b;
            color: #fff;
        }
        .btn-pending:hover {
            background: #d97706;
        }
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-pending {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }
        .status-approved {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        .status-rejected {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: #1a2332;
            padding: 2rem;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            color: #8b92a0;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            background: #0f1419;
            border: 1px solid #2d3748;
            border-radius: 6px;
            color: #fff;
            font-family: inherit;
            resize: vertical;
        }
        .modal-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .btn-cancel {
            background: #6b7280;
            color: #fff;
        }
        .btn-cancel:hover {
            background: #4b5563;
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detail Verifikasi Kursus</h1>
        <a href="{{ route('admin.verifikasi') }}" class="btn-back">← Kembali</a>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <!-- Informasi Kursus -->
        <div class="detail-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div class="card-title">Informasi Kursus</div>
                <span class="status-badge status-{{ $requestData->status }}">
                    {{ ucfirst($requestData->status) }}
                </span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Kursus</div>
                    <div class="info-value">{{ $requestData->nama_kursus }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $requestData->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nomor HP</div>
                    <div class="info-value">{{ $requestData->nomor_hp }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Lokasi</div>
                    <div class="info-value">{{ $requestData->lokasi }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Jam Operasional</div>
                    <div class="info-value">{{ $requestData->jam_buka }} - {{ $requestData->jam_tutup }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Pengajuan</div>
                    <div class="info-value">
                        @if($requestData->waktu && $requestData->waktu instanceof \Carbon\Carbon)
                            {{ $requestData->waktu->format('d M Y, H:i') }}
                        @elseif($requestData->waktu)
                            {{ $requestData->waktu }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumen Pendukung -->
        <div class="detail-card">
            <div class="card-title">Dokumen Pendukung</div>
            
            <div class="document-grid">
                <!-- KTP -->
                <div class="document-item">
                    <h4>📄 KTP Pemilik</h4>
                    <div class="document-preview">
                        @if($requestData->dokumenKursus && $requestData->dokumenKursus->ktp)
                            <img src="{{ $requestData->dokumenKursus->ktp }}" alt="KTP" loading="lazy">
                        @else
                            <div class="document-icon">📄</div>
                        @endif
                    </div>
                    @if($requestData->dokumenKursus && $requestData->dokumenKursus->ktp)
                        <a href="{{ $requestData->dokumenKursus->ktp }}" target="_blank" class="btn-view">Lihat Dokumen</a>
                    @else
                        <span style="color: #8b92a0; font-size: 0.875rem;">Tidak ada dokumen</span>
                    @endif
                </div>

                <!-- Izin Usaha -->
                <div class="document-item">
                    <h4>📋 Izin Usaha/SIUP</h4>
                    <div class="document-preview">
                        @if($requestData->dokumenKursus && $requestData->dokumenKursus->izin_usaha)
                            <img src="{{ $requestData->dokumenKursus->izin_usaha }}" alt="Izin Usaha" loading="lazy">
                        @else
                            <div class="document-icon">📋</div>
                        @endif
                    </div>
                    @if($requestData->dokumenKursus && $requestData->dokumenKursus->izin_usaha)
                        <a href="{{ $requestData->dokumenKursus->izin_usaha }}" target="_blank" class="btn-view">Lihat Dokumen</a>
                    @else
                        <span style="color: #8b92a0; font-size: 0.875rem;">Tidak ada dokumen</span>
                    @endif
                </div>

                <!-- Sertifikat Instruktur -->
                <div class="document-item">
                    <h4>🎓 Sertifikat Instruktur</h4>
                    <div class="document-preview">
                        @if($requestData->dokumenKursus && $requestData->dokumenKursus->sertif_instruktur)
                            <img src="{{ $requestData->dokumenKursus->sertif_instruktur }}" alt="Sertifikat" loading="lazy">
                        @else
                            <div class="document-icon">🎓</div>
                        @endif
                    </div>
                    @if($requestData->dokumenKursus && $requestData->dokumenKursus->sertif_instruktur)
                        <a href="{{ $requestData->dokumenKursus->sertif_instruktur }}" target="_blank" class="btn-view">Lihat Dokumen</a>
                    @else
                        <span style="color: #8b92a0; font-size: 0.875rem;">Opsional - Tidak diupload</span>
                    @endif
                </div>

                <!-- Dokumen Legal -->
                <div class="document-item">
                    <h4>📑 Dokumen Legal Lainnya</h4>
                    <div class="document-preview">
                        @if($requestData->dokumenKursus && $requestData->dokumenKursus->dokumen_legal)
                            <img src="{{ $requestData->dokumenKursus->dokumen_legal }}" alt="Dokumen Legal" loading="lazy">
                        @else
                            <div class="document-icon">📑</div>
                        @endif
                    </div>
                    @if($requestData->dokumenKursus && $requestData->dokumenKursus->dokumen_legal)
                        <a href="{{ $requestData->dokumenKursus->dokumen_legal }}" target="_blank" class="btn-view">Lihat Dokumen</a>
                    @else
                        <span style="color: #8b92a0; font-size: 0.875rem;">Opsional - Tidak diupload</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($requestData->status === 'pending')
            <div class="detail-card">
                <div class="card-title">Aksi Verifikasi</div>
                <div class="action-buttons">
                    <form method="POST" action="{{ route('admin.verifikasi.approve', $requestData->id_request) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-approve" onclick="return confirm('Apakah Anda yakin ingin menyetujui request ini?')">
                            ✓ Setujui
                        </button>
                    </form>

                    <button type="button" class="btn btn-reject" onclick="showRejectModal()">
                        ✗ Tolak
                    </button>
                </div>
            </div>
        @else
            <div class="detail-card">
                <div class="card-title">Aksi</div>
                <div class="action-buttons">
                    <form method="POST" action="{{ route('admin.verifikasi.pending', $requestData->id_request) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-pending" onclick="return confirm('Kembalikan status ke pending?')">
                            ⟲ Kembalikan ke Pending
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <div class="modal-title">Tolak Request</div>
            <form method="POST" action="{{ route('admin.verifikasi.reject', $requestData->id_request) }}">
                @csrf
                <div class="form-group">
                    <label for="alasan">Alasan Penolakan *</label>
                    <textarea id="alasan" name="alasan" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-cancel" onclick="hideRejectModal()">Batal</button>
                    <button type="submit" class="btn btn-reject">Tolak Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').classList.add('active');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });
    </script>
</body>
</html>
