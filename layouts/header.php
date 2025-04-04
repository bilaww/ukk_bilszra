<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Website Pengaduan Siswa</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary"> <!-- Navbar diberi warna biru -->
        <div class="container">
            <a class="navbar-brand text-white" href="http://localhost/UKK_BILSZRA/index.php">Website Pengaduan Siswa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                
                <?php if (isset($_SESSION['login']) && isset($_SESSION['level'])) { ?>

                    <?php if ($_SESSION['level'] === 'admin' || $_SESSION['level'] === 'petugas') { ?>
                        <li class="nav-item"><a class="nav-link text-white" href="index.php?page=tanggapan">Data Tanggapan</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="index.php?page=pengaduan">Data Pengaduan</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="index.php?page=siswa">Data Siswa</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="index.php?page=petugas">Data Petugas</a></li>
                    <?php } ?>

                    <li class="nav-item"><a class="nav-link text-white" href="../config/aksi_logout.php">Keluar</a></li>

                <?php } else { ?>
                    <li class="nav-item"><a class="nav-link text-white" href="../config/aksi_logout.php">Keluar</a></li>
                <?php } ?>

                </ul>
            </div>
        </div>
    </nav>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
