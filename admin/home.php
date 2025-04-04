<?php
// Konfigurasi database
$host = "localhost";
$user = "root"; // Sesuaikan dengan user database Anda
$pass = ""; // Sesuaikan dengan password database Anda
$db   = "ukk_bilszra"; // Sesuaikan dengan nama database Anda

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query untuk menghitung jumlah siswa
$result_siswa = mysqli_query($conn, "SELECT COUNT(*) AS total FROM siswa");
$row_siswa = mysqli_fetch_assoc($result_siswa);
$total_siswa = $row_siswa['total'];

// Query untuk menghitung jumlah pengaduan
$result_pengaduan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pengaduan");
$row_pengaduan = mysqli_fetch_assoc($result_pengaduan);
$total_pengaduan = $row_pengaduan['total'];

// Query untuk menghitung jumlah tanggapan
$result_tanggapan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tanggapan");
$row_tanggapan = mysqli_fetch_assoc($result_tanggapan);
$total_tanggapan = $row_tanggapan['total'];

// Query untuk menghitung jumlah petugas
$result_petugas = mysqli_query($conn, "SELECT COUNT(*) AS total FROM petugas");
$row_petugas = mysqli_fetch_assoc($result_petugas);
$total_petugas = $row_petugas['total'];

// Tutup koneksi database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            background: #e9ecef;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            font-weight: bold;
            background: #ced4da;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .card-body {
            text-align: center;
            font-size: 1.2rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3 class="mb-4 text-center">Dashboard</h3>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Siswa</div>
                    <div class="card-body"><?= $total_siswa ?> Siswa</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Pengaduan</div>
                    <div class="card-body"><?= $total_pengaduan ?> Aduan</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Tanggapan</div>
                    <div class="card-body"><?= $total_tanggapan ?> Tanggapan</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Petugas</div>
                    <div class="card-body"><?= $total_petugas ?> Pengguna</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
