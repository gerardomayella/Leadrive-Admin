<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Data Akun Dasar | LeadDrive</title>
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

        /* Progress Indicator */
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

        /* Form Card */
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
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

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #444;
            border-radius: 6px;
            background-color: #333;
            color: #fff;
            font-size: 1rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff7f00;
        }

        .password-requirements {
            margin-top: 1rem;
            padding: 1rem;
            background-color: #1c1c1c;
            border-radius: 6px;
        }

        .password-requirements h4 {
            color: #ff7f00;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .password-requirements ul {
            list-style: none;
            padding-left: 0;
            color: #ccc;
            font-size: 0.9rem;
        }

        .password-requirements li {
            margin-bottom: 0.3rem;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle input {
            padding-right: 40px;
        }

        .password-toggle-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 1.2rem;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-top: 0.3rem;
        }

        .checkbox-group label {
            color: #fff;
            font-size: 0.9rem;
        }

        .checkbox-group a {
            color: #4a9eff;
            text-decoration: none;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            padding: 1rem;
            background-color: #2c2c2c;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #3c3c3c;
        }

        .error-message {
            color: #ff4444;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Progress Indicator -->
        <div class="progress-container">
            <div class="progress-step step-active">1</div>
            <div class="progress-step step-pending">2</div>
            <div class="progress-step step-pending">3</div>
        </div>

        <!-- Step Title -->
        <div class="step-title">
            <h1>Data Akun Dasar</h1>
            <p>Lengkapi informasi akun Anda</p>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <h2>Daftar sebagai Pemilik Kursus</h2>

            @if ($errors->any())
                <div style="background-color: #ff4444; color: #fff; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                    <ul style="list-style: none; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.step1.post') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nomor_hp">Nomor HP <span class="required">*</span></label>
                        <input type="text" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp') }}" required>
                        @error('nomor_hp')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password <span class="required">*</span></label>
                        <div class="password-toggle">
                            <input type="password" id="password" name="password" required>
                            <span class="password-toggle-icon" onclick="togglePassword('password', this)">👁️</span>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                    <div class="password-toggle">
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                        <span class="password-toggle-icon" onclick="togglePassword('password_confirmation', this)">👁️</span>
                    </div>
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="password-requirements">
                    <h4>Syarat Password:</h4>
                    <ul>
                        <li>• Minimal 8 karakter</li>
                        <li>• Mengandung huruf besar dan kecil</li>
                        <li>• Mengandung angka</li>
                    </ul>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms" name="terms" value="1" required>
                    <label for="terms">
                        Saya menyetujui <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi LeadDrive</a>
                    </label>
                </div>
                @error('terms')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn-primary">Lanjutkan ke Data Kursus</button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                iconElement.textContent = '👁️‍🗨️';
            } else {
                input.type = 'password';
                iconElement.textContent = '👁️';
            }
        }
    </script>
</body>
</html>

