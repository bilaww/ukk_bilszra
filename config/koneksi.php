<?php
$koneksi = mysqli_connect("localhost","root","","ukk_bilszra");

if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
?>