<?php
session_start();
include 'koneksi.php';

// Pastikan input tidak kosong
if (!isset($_POST['email'], $_POST['password'], $_POST['level'])) {
    echo "<script>alert('Silakan isi email, password, dan level.'); window.location='../index.php';</script>";
    exit;
}

$email = strtolower(trim($_POST['email']));
$password = trim($_POST['password']);
$level = trim($_POST['level']);

// Pastikan level yang dipilih valid
$valid_levels = ['siswa', 'petugas', 'admin'];
if (!in_array($level, $valid_levels)) {
    echo "<script>alert('Level tidak valid!'); window.location='../index.php';</script>";
    exit;
}

// Pilih tabel berdasarkan level
if ($level === 'siswa') {
    $stmt = $koneksi->prepare("SELECT nisn, nama, email, password FROM siswa WHERE email = ?");
} else { // Petugas & Admin masuk ke tabel petugas
    $stmt = $koneksi->prepare("SELECT id_petugas, nama_petugas, email, password, level FROM petugas WHERE email = ?");
}

// Cek jika query gagal
if (!$stmt) {
    die("Query Error: " . $koneksi->error);
}

// Bind parameter dan eksekusi
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    $password_db = $data['password'];
    $login_valid = false;

    // Cek apakah password cocok
    if (password_verify($password, $password_db)) {
        $login_valid = true;
    }

    if ($login_valid) {
        $_SESSION['login'] = $level;

        if ($level === 'siswa') {
            $_SESSION['nisn'] = $data['nisn']; // Menggunakan nisn sebagai id siswa
            $_SESSION['nama'] = $data['nama'];
            header("Location: ../siswa/");
        } else { // Petugas dan Admin masuk ke folder admin
            $_SESSION['id_petugas'] = $data['id_petugas'];
            $_SESSION['nama_petugas'] = $data['nama_petugas'];
            $_SESSION['level'] = $data['level'];
            header("Location: ../admin/");
        }
        exit;
    } else {
        echo "<script>alert('Password salah!'); window.location='../index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Email tidak ditemukan!'); window.location='../index.php';</script>";
    exit;
}
?>
