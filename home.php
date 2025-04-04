<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pengaduan Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 1100px;
        }
        .card {
            border-radius: 15px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border: none;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            color: white;
            font-weight: bold;
            text-align: center;
            border-radius: 15px 15px 0 0;
            font-size: 18px;
            padding: 15px;
        }
        .card-body {
            font-size: 18px;
            color: #333;
            padding: 25px;
        }
        .info-title {
            font-weight: bold;
            font-size: 20px;
            color: #0056b3;
        }
        .header-title {
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            color: #0056b3;
            font-weight: 700;
            font-size: 32px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .header-title span {
            display: block;
            font-size: 20px;
            font-weight: 600;
            color: #007bff;
        }
        .header-title hr {
            border: 3px solid #0056b3;
            width: 120px;
            margin: 15px auto;
            border-radius: 2px;
        }
        .footer {
            text-align: center;
            padding: 15px;
            background: #0056b3;
            color: white;
            font-size: 16px;
            margin-top: 30px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="text-center mb-4">
        <h4 class="header-title">
            Sistem Pengaduan
            <span>SISWA</span>
        </h4>
        <hr>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">INFORMASI</div>
                <div class="card-body">
                    <p class="info-title">Tentang Website</p>
                    <p>Website pengaduan siswa ini dibuat untuk memenuhi tugas Uji Kompetensi Keahlian RPL Tahun 2025.</p>
                    <p class="info-title">Petunjuk Penggunaan</p>
                    <ul>
                        <li>Login terlebih dahulu sebelum membuat pengaduan.</li>
                        <li>Isi formulir pengaduan dengan benar dan lengkap.</li>
                        <li>Pengaduan akan ditindaklanjuti dalam waktu 24 jam.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">KONTAK</div>
                <div class="card-body">
                    <p><strong>Nama:</strong> Nama Siswa</p>
                    <p><strong>Kelas:</strong> Kelas Berapa</p>
                    <p><strong>No.Tlp:</strong> Berapa</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
