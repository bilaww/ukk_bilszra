<?php
include 'config/koneksi.php'; // Pastikan koneksi sudah di-include

// Logika Hapus Data Pengaduan dan Gambar
if (isset($_GET['hapus'])) {
  $id_pengaduan = $_GET['hapus'];

  // Mendapatkan nama file gambar dari database
  $gambar_query = mysqli_query($koneksi, "SELECT foto FROM pengaduan WHERE id_pengaduan='$id_pengaduan'");
  $gambar_data = mysqli_fetch_assoc($gambar_query);
  $foto = $gambar_data['foto'];

  // Menghapus file gambar jika ada
  if (!empty($foto)) {
    $foto_path = '../assets/img/' . $foto; // Sesuaikan dengan direktori tempat gambar disimpan
    if (file_exists($foto_path)) {
      unlink($foto_path); // Menghapus file gambar dari server
    }
  }

  // Hapus data pengaduan dari database
  $hapus_query = mysqli_query($koneksi, "DELETE FROM pengaduan WHERE id_pengaduan='$id_pengaduan'");

  if ($hapus_query) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({
        title: 'Terhapus!',
        text: 'Data berhasil dihapus',
        icon: 'success'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'index.php?page=pengaduan';
        }
      });
    </script>";
  } else {
    echo "<script>alert('Gagal menghapus data!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Website Pengaduan Siswa | Bilszra</title>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  
  <!-- Custom CSS -->
  <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }
    .navbar {
        background-color: #007BFF !important;
    }
    .navbar-brand, .nav-link {
        color: white !important;
        font-weight: bold;
    }
    .nav-link:hover {
        color: #f8f9fa !important;
    }
    .container-content {
        max-width: 700px;
        margin: 40px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    .footer {
        background: #343a40;
        color: white;
        padding: 10px 0;
        text-align: center;
        margin-top: 40px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Pengaduan Siswa</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
      <li class="nav-item">
  <a class="nav-link me-3" href="index.php?page=registrasi">Daftar Akun</a>
</li>
<li class="nav-item">
  <a class="nav-link" href="index.php?page=login">Login</a>
</li>
      </ul>
    </div>
  </div>
</nav>

<!-- Konten -->
<div class="container-content">
  <?php
  if (isset($_GET['page'])) {
    $page = $_GET['page'];

    switch ($page) {
      case 'login':
        include 'login.php';
        break;
      case 'registrasi':
        include 'registrasi.php';
        break;
      case 'registrasi_petugas':
        include 'registrasi_petugas.php';
        break;
      default:
        echo "<h4 class='text-center text-danger'>Halaman tidak tersedia</h4>";
        break;
    }
  } else {
    include 'home.php';
  }


if (isset($_POST['submit_pengaduan'])) {
    $siswa_id = $_POST['siswa_id'];
    $isi_pengaduan = $_POST['isi_pengaduan'];

    $query = "INSERT INTO pengaduan (siswa_id, isi, status) VALUES ('$siswa_id', '$isi_pengaduan', 'pending')";
    if (mysqli_query($conn, $query)) {
        // Ambil email petugas dari database
        $result = mysqli_query($conn, "SELECT email FROM petugas WHERE role = 'petugas' LIMIT 1");
        $row = mysqli_fetch_assoc($result);
        $email_petugas = $row['email'];

        // Kirim notifikasi ke petugas
        $subject_petugas = "Pengaduan Baru dari Siswa";
        $message_petugas = "Ada pengaduan baru dari siswa. Silakan cek sistem.";
        kirimEmail($email_petugas, $subject_petugas, $message_petugas);

        echo "Pengaduan berhasil dikirim! Notifikasi ke petugas sudah dikirim.";
    } else {
        echo "Terjadi kesalahan!";
    }
}

  ?>
</div>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <p>UKK RPL 2025 | Bilszra | SMKN 2 BANGKALAN</p>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
