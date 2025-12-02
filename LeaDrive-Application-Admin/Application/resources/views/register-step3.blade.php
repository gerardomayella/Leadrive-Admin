<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Upload Dokumen | LeadDrive</title>
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
            max-width: 900px;
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

        .info-box {
            background-color: #ff7f0020;
            border: 1px solid #ff7f00;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-box h4 {
            color: #ff7f00;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box ul {
            list-style: none;
            padding-left: 0;
            color: #ccc;
        }

        .info-box li {
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .info-box li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #ff7f00;
        }

        .upload-section {
            margin-bottom: 2rem;
        }

        .upload-section label {
            display: block;
            margin-bottom: 0.5rem;
            color: #fff;
            font-weight: 500;
        }

        .upload-section label .required {
            color: #ff7f00;
        }

        .upload-section label .optional {
            color: #888;
            font-size: 0.9rem;
        }

        .upload-area {
            border: 2px dashed #666;
            border-radius: 8px;
            padding: 3rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #333;
        }

        .upload-area:hover {
            border-color: #ff7f00;
            background-color: #3c3c3c;
        }

        .upload-area.dragover {
            border-color: #ff7f00;
            background-color: #ff7f0020;
        }

        .upload-icon {
            font-size: 3rem;
            color: #ff7f00;
            margin-bottom: 1rem;
        }

        .upload-text {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .upload-format {
            color: #888;
            font-size: 0.9rem;
        }

        .upload-input {
            display: none;
        }

        .file-preview {
            margin-top: 1rem;
            padding: 1rem;
            background-color: #1c1c1c;
            border-radius: 6px;
            display: none;
        }

        .file-preview.show {
            display: block;
        }

        .file-preview-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem;
            background-color: #333;
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }

        .file-preview-item:last-child {
            margin-bottom: 0;
        }

        .file-name {
            color: #fff;
            flex: 1;
        }

        .file-remove {
            background-color: #ff4444;
            color: #fff;
            border: none;
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .file-remove:hover {
            background-color: #cc3333;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-back,
        .btn-submit {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-back {
            background-color: #2c2c2c;
            color: #fff;
        }

        .btn-back:hover {
            background-color: #3c3c3c;
        }

        .btn-submit {
            background-color: #2c2c2c;
            color: #fff;
        }

        .btn-submit:hover {
            background-color: #3c3c3c;
        }

        .error-message {
            color: #ff4444;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Progress Indicator -->
        <div class="progress-container">
            <div class="progress-step step-completed">1</div>
            <div class="progress-step step-completed">2</div>
            <div class="progress-step step-active">3</div>
        </div>

        <!-- Step Title -->
        <div class="step-title">
            <h1>Upload Dokumen</h1>
            <p>Upload dokumen pendukung</p>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <h2>Upload Dokumen Pendukung</h2>

            @if ($errors->any())
                <div style="background-color: #ff4444; color: #fff; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <ul style="list-style: none; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="info-box">
                <h4>ℹ️ Dokumen yang Diperlukan:</h4>
                <ul>
                    <li>KTP pemilik kursus (wajib)</li>
                    <li>Izin usaha/SIUP (wajib)</li>
                    <li>Sertifikat instruktur (opsional)</li>
                    <li>Dokumen legal lainnya (opsional)</li>
                </ul>
            </div>

            <form action="{{ route('register.step3.post') }}" method="POST" enctype="multipart/form-data" id="step3Form">
                @csrf

                <!-- KTP Upload -->
                <div class="upload-section">
                    <label for="ktp">
                        KTP Pemilik Kursus <span class="required">*</span>
                    </label>
                    <div class="upload-area" onclick="document.getElementById('ktp').click()" 
                         ondrop="handleDrop(event, 'ktp')" 
                         ondragover="handleDragOver(event)" 
                         ondragleave="handleDragLeave(event)">
                        <div class="upload-icon">☁️ ⬆️</div>
                        <div class="upload-text">Klik untuk upload KTP</div>
                        <div class="upload-format">Format: JPG, PNG, PDF (Max 5MB)</div>
                    </div>
                    <input type="file" id="ktp" name="ktp" class="upload-input" accept=".jpg,.jpeg,.png,.pdf" required onchange="handleFileSelect(this, 'ktp')">
                    <div id="ktp-preview" class="file-preview"></div>
                    @error('ktp')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Izin Usaha Upload -->
                <div class="upload-section">
                    <label for="izin_usaha">
                        Izin Usaha/SIUP <span class="required">*</span>
                    </label>
                    <div class="upload-area" onclick="document.getElementById('izin_usaha').click()" 
                         ondrop="handleDrop(event, 'izin_usaha')" 
                         ondragover="handleDragOver(event)" 
                         ondragleave="handleDragLeave(event)">
                        <div class="upload-icon">☁️ ⬆️</div>
                        <div class="upload-text">Klik untuk upload Izin Usaha</div>
                        <div class="upload-format">Format: JPG, PNG, PDF (Max 5MB)</div>
                    </div>
                    <input type="file" id="izin_usaha" name="izin_usaha" class="upload-input" accept=".jpg,.jpeg,.png,.pdf" required onchange="handleFileSelect(this, 'izin_usaha')">
                    <div id="izin_usaha-preview" class="file-preview"></div>
                    @error('izin_usaha')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Sertifikat Instruktur Upload -->
                <div class="upload-section">
                    <label for="sertif_instruktur">
                        Sertifikat Instruktur <span class="optional">(Opsional)</span>
                    </label>
                    <div class="upload-area" onclick="document.getElementById('sertif_instruktur').click()" 
                         ondrop="handleDrop(event, 'sertif_instruktur')" 
                         ondragover="handleDragOver(event)" 
                         ondragleave="handleDragLeave(event)">
                        <div class="upload-icon">☁️ ⬆️</div>
                        <div class="upload-text">Klik untuk upload Sertifikat</div>
                        <div class="upload-format">Format: JPG, PNG, PDF (Max 5MB)</div>
                    </div>
                    <input type="file" id="sertif_instruktur" name="sertif_instruktur" class="upload-input" accept=".jpg,.jpeg,.png,.pdf" onchange="handleFileSelect(this, 'sertif_instruktur')">
                    <div id="sertif_instruktur-preview" class="file-preview"></div>
                    @error('sertif_instruktur')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dokumen Legal Upload -->
                <div class="upload-section">
                    <label for="dokumen_legal">
                        Dokumen Legal Lainnya <span class="optional">(Opsional)</span>
                    </label>
                    <div class="upload-area" onclick="document.getElementById('dokumen_legal').click()" 
                         ondrop="handleDrop(event, 'dokumen_legal')" 
                         ondragover="handleDragOver(event)" 
                         ondragleave="handleDragLeave(event)">
                        <div class="upload-icon">☁️ ⬆️</div>
                        <div class="upload-text">Klik untuk upload Dokumen Lain</div>
                        <div class="upload-format">Format: JPG, PNG, PDF (Max 5MB per file)</div>
                    </div>
                    <input type="file" id="dokumen_legal" name="dokumen_legal" class="upload-input" accept=".jpg,.jpeg,.png,.pdf" onchange="handleFileSelect(this, 'dokumen_legal')">
                    <div id="dokumen_legal-preview" class="file-preview"></div>
                    @error('dokumen_legal')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('register.back', 3) }}" class="btn-back">Kembali</a>
                    <button type="submit" class="btn-submit">
                        <span>✈️</span> Kirim Registrasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function handleFileSelect(input, fieldName) {
            const file = input.files[0];
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File terlalu besar. Maksimal 5MB.');
                    input.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                    input.value = '';
                    return;
                }

                showFilePreview(file, fieldName);
            }
        }

        function showFilePreview(file, fieldName) {
            const preview = document.getElementById(fieldName + '-preview');
            preview.innerHTML = `
                <div class="file-preview-item">
                    <span class="file-name">${file.name}</span>
                    <button type="button" class="file-remove" onclick="removeFile('${fieldName}')">Hapus</button>
                </div>
            `;
            preview.classList.add('show');
        }

        function removeFile(fieldName) {
            const input = document.getElementById(fieldName);
            const preview = document.getElementById(fieldName + '-preview');
            input.value = '';
            preview.classList.remove('show');
            preview.innerHTML = '';
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            e.currentTarget.classList.add('dragover');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            e.currentTarget.classList.remove('dragover');
        }

        function handleDrop(e, fieldName) {
            e.preventDefault();
            e.stopPropagation();
            e.currentTarget.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const input = document.getElementById(fieldName);
                input.files = files;
                handleFileSelect(input, fieldName);
            }
        }
    </script>
</body>
</html>

