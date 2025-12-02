<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Data Kursus | LeadDrive</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #1c1c1c;
            color: #fff;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .progress-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .progress-step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .step-completed {
            background-color: #4caf50;
            color: #fff;
        }

        .step-active {
            background-color: #ff7f00;
            color: #fff;
        }

        .step-pending {
            background-color: #444;
            color: #fff;
        }

        .step-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .step-title h1 {
            color: #ff7f00;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .step-title p {
            color: #ccc;
        }

        .form-card {
            background-color: #2c2c2c;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .form-card h2 {
            color: #ff7f00;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #fff;
            font-weight: 500;
        }

        .form-group label .required {
            color: #ff7f00;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #444;
            border-radius: 6px;
            background-color: #333;
            color: #fff;
            font-size: 1rem;
            font-family: Arial, sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ff7f00;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .gps-section {
            border: 2px dashed #4caf50;
            border-radius: 8px;
            padding: 3rem;
            text-align: center;
            background-color: #1a3d1a;
            margin-bottom: 1.5rem;
            cursor: pointer;
        }

        .gps-section:hover {
            background-color: #1f4f1f;
        }

        .gps-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .gps-selected {
            background-color: #1a3d1a;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            color: #4caf50;
        }

        .gps-selected::before {
            content: "✓ ";
            color: #4caf50;
        }

        .vehicle-types {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
            max-width: 500px;
        }

        .vehicle-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background-color: #333;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .vehicle-item:hover {
            background-color: #3c3c3c;
        }

        .vehicle-item input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        .vehicle-item label {
            margin: 0;
            cursor: pointer;
            flex: 1;
        }

        .paket-section {
            margin-bottom: 2rem;
        }

        .paket-item {
            background-color: #333;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .paket-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .paket-item-header h4 {
            color: #ff7f00;
        }

        .paket-item-remove {
            background-color: #ff4444;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .paket-item-remove:hover {
            background-color: #cc3333;
        }

        .paket-form-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .btn-add-paket {
            background-color: #2c2c2c;
            color: #fff;
            border: 2px dashed #666;
            padding: 1rem;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: border-color 0.3s;
        }

        .btn-add-paket:hover {
            border-color: #ff7f00;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-back,
        .btn-next {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-back {
            background-color: #2c2c2c;
            color: #fff;
        }

        .btn-back:hover {
            background-color: #3c3c3c;
        }

        .btn-next {
            background-color: #2c2c2c;
            color: #fff;
        }

        .btn-next:hover {
            background-color: #3c3c3c;
        }

        .time-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .error-message {
            color: #ff4444;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .vehicle-types {
                grid-template-columns: 1fr;
            }

            .paket-form-row {
                grid-template-columns: 1fr;
            }

            .time-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Progress Indicator -->
        <div class="progress-container">
            <div class="progress-step step-completed">1</div>
            <div class="progress-step step-active">2</div>
            <div class="progress-step step-pending">3</div>
        </div>

        <!-- Step Title -->
        <div class="step-title">
            <h1>Data Kursus</h1>
            <p>Isi detail kursus mengemudi</p>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <h2>Registrasi Kursus Mengemudi</h2>

            @if ($errors->any())
                <div style="background-color: #ff4444; color: #fff; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <ul style="list-style: none; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.step2.post') }}" method="POST" id="step2Form">
                @csrf

                <div class="form-group">
                    <label for="nama_kursus">Nama Kursus/Instansi <span class="required">*</span></label>
                    <input type="text" id="nama_kursus" name="nama_kursus" value="{{ old('nama_kursus') }}" required>
                    @error('nama_kursus')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="lokasi">Alamat Lengkap <span class="required">*</span></label>
                    <textarea id="lokasi" name="lokasi" required>{{ old('lokasi') }}</textarea>
                    @error('lokasi')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="time-row">
                    <div class="form-group">
                        <label for="jam_buka">Jam Buka <span class="required">*</span></label>
                        <input type="time" id="jam_buka" name="jam_buka" value="{{ old('jam_buka') }}" required>
                        @error('jam_buka')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jam_tutup">Jam Tutup <span class="required">*</span></label>
                        <input type="time" id="jam_tutup" name="jam_tutup" value="{{ old('jam_tutup') }}" required>
                        @error('jam_tutup')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('register.back', 2) }}" class="btn-back">Kembali</a>
                    <button type="submit" class="btn-next">Lanjutkan ke Dokumen</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

