<?php
include 'config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_petugas = trim($_POST["nama_petugas"]);
    $email = strtolower(trim($_POST["email"]));
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Enkripsi password
    $telp = trim($_POST["telp"]);
    $level = "petugas"; // Level otomatis petugas

    // Cek apakah email sudah digunakan
    $cek_email = $koneksi->prepare("SELECT email FROM petugas WHERE email = ?");
    $cek_email->bind_param("s", $email);
    $cek_email->execute();
    $cek_email->store_result();

    if ($cek_email->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location='register_petugas.php';</script>";
        exit;
    }
    
    // Masukkan data ke database
    $stmt = $koneksi->prepare("INSERT INTO petugas (nama_petugas, email, password, telp, level) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama_petugas, $email, $password, $telp, $level);
    
    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal!'); window.location='register_petugas.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-center bg-success text-white rounded-top-4">
                    <h2 class="fw-bold mb-0">Registrasi Petugas</h2>
                </div>
                <div class="card-body p-4">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-5">Nama Lengkap</label>
                            <input type="text" class="form-control p-3 fs-5" name="nama_petugas" placeholder="Masukkan Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-5">Email</label>
                            <input type="email" class="form-control p-3 fs-5" name="email" placeholder="Masukkan Email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-5">Password</label>
                            <input type="password" class="form-control p-3 fs-5" name="password" placeholder="Masukkan Password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-5">No. Telepon</label>
                            <input type="text" class="form-control p-3 fs-5" name="telp" placeholder="Masukkan No. Telepon" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg btn-success fw-bold p-3 fs-4">Daftar</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light rounded-bottom-4 p-3">
                    <a href="index.php" class="text-decoration-none text-success fw-semibold fs-5">Sudah punya akun? Login di sini!</a>
                </div>
            </div>
        </div>    
    </div>
</div>
</body>
</html>
