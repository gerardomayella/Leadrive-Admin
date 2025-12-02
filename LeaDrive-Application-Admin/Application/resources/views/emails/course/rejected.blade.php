<!DOCTYPE html>
<html>
<head>
    <title>Status Verifikasi Kursus</title>
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
            background-color: #ef4444;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
        }
        .reason-box {
            background-color: #f9f9f9;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Status Verifikasi Kursus</h1>
        </div>
        <div class="content">
            <p>Halo {{ $requestData->nama_kursus }},</p>
            <p>Terima kasih telah mendaftar di <strong>LeadDrive</strong>.</p>
            <p>Setelah melakukan peninjauan terhadap data dan dokumen yang Anda kirimkan, kami mohon maaf bahwa pendaftaran kursus Anda <strong>belum dapat kami setujui</strong> saat ini.</p>
            
            <div class="reason-box">
                <strong>Alasan Penolakan:</strong><br>
                {{ $reason }}
            </div>

            <p>Anda dapat memperbaiki data atau melengkapi dokumen yang diperlukan dan mengajukan pendaftaran kembali.</p>
            <p>Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi tim support kami.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} LeadDrive. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
