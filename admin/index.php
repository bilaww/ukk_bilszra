<?php
session_start();
include '../layouts/header.php'; 

if (isset($_GET['page'])) {
    $page = $_GET['page'];

    switch ($page) {
        case 'pengaduan':
            if (file_exists('data_pengaduan.php')) {
                include 'data_pengaduan.php';
            } else {
                echo "<h3>Halaman tidak ditemukan</h3>";
            }
            break;
        case 'tanggapan':
            if (file_exists('data_tanggapan.php')) {
                include 'data_tanggapan.php';
            } else {
                echo "<h3>Halaman tidak ditemukan</h3>";
            }
            break;
        case 'petugas':
            if (file_exists('data_petugas.php')) {
                include 'data_petugas.php';
            } else {
                echo "<h3>Halaman tidak ditemukan</h3>";
            }
            break;
        case 'siswa':
            if (file_exists('data_siswa.php')) {
                include 'data_siswa.php';
            } else {
                echo "<h3>Halaman tidak ditemukan</h3>";
            }
            break;
        default:
            echo "<h3>Halaman tidak tersedia</h3>";
            break;
    }
} else {
    include 'home.php';
}

include '../layouts/footer.php'; 
?>
