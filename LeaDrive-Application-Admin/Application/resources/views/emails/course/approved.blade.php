<!DOCTYPE html>
<html>
<head>
    <title>Kursus Disetujui</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #10b981;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8em;
            color: #777;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat! Kursus Anda Disetujui</h1>
        </div>
        <div class="content">
            <p>Halo {{ $kursus->nama_kursus }},</p>
            <p>Kami dengan senang hati menginformasikan bahwa pendaftaran kursus Anda di <strong>LeadDrive</strong> telah disetujui.</p>
            <p>Sekarang Anda dapat masuk ke dashboard kursus Anda dan mulai mengelola layanan Anda.</p>
            <p>Detail Akun:</p>
            <ul>
                <li><strong>Nama Kursus:</strong> {{ $kursus->nama_kursus }}</li>
                <li><strong>Email:</strong> {{ $kursus->email }}</li>
            </ul>
            <p>Silakan login menggunakan email dan password yang telah Anda daftarkan.</p>
            <center>
                <a href="{{ url('/login') }}" class="button">Masuk ke Dashboard</a>
            </center>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} LeadDrive. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
