<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP PANDA</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            margin: 20px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .title {
            color: #4f46e5;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 16px;
        }

        .otp-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 0;
        }

        .otp-label {
            color: #e0e7ff;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .warning-icon {
            color: #f59e0b;
            font-size: 18px;
            margin-right: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }

        .footer-logo {
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">🐼</div>
            <h1 class="title">PANDA</h1>
            <p class="subtitle">Platform Pembelajaran Anak</p>
        </div>

        <div class="otp-container">
            <div class="otp-label">Kode OTP Anda</div>
            <div class="otp-code">{{ $otpCode }}</div>
        </div>

        <div class="warning">
            <span class="warning-icon">⚠️</span>
            <strong>Penting:</strong> Kode OTP ini hanya berlaku selama 5 menit. Jangan bagikan kode ini kepada
            siapapun.
        </div>

        <p>
            Halo! Terima kasih telah menggunakan PANDA. Masukkan kode OTP di atas untuk melanjutkan proses login ke akun
            Anda.
        </p>

        <p>
            Jika Anda tidak meminta kode OTP ini, abaikan email ini.
        </p>

        <div class="footer">
            <div class="footer-logo">🐼</div>
            <p>
                <strong>PANDA</strong><br>
                Platform Pembelajaran Anak Digital<br>
                © 2025 PANDA. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
