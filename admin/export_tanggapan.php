<?php
// Pastikan tidak ada output sebelum header
ob_start();
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=Data_Tanggapan_Pengaduan.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ukk_bilszra";

$koneksi = mysqli_connect($servername, $username, $password, $dbname);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Header laporan (judul)
echo "LAPORAN PENGADUAN DAN TANGGAPAN\n";
echo "UKK REKAYASA PERANGKAT LUNAK\n\n";

// Header tabel
echo "NO\tTANGGAL\tNISN\tJUDUL LAPORAN\tISI LAPORAN\tTANGGAPAN\tSTATUS\n";

// Query data dari database
$no = 1;
$query = mysqli_query($koneksi, "
    SELECT 
        a.tgl_tanggapan, b.nisn AS nisn, b.judul_laporan, b.isi_laporan, 
        a.tanggapan, b.status
    FROM tanggapan a 
    INNER JOIN pengaduan b ON a.id_pengaduan = b.id_pengaduan 
    ORDER BY a.tgl_tanggapan DESC
");

while ($data = mysqli_fetch_assoc($query)) {
    // Data yang akan ditampilkan
    $tgl_tanggapan = isset($data['tgl_tanggapan']) ? $data['tgl_tanggapan'] : '-';
    $nisn = isset($data['nisn']) ? $data['nisn'] : '-';
    $judul = isset($data['judul_laporan']) ? $data['judul_laporan'] : '-';
    $isi = isset($data['isi_laporan']) ? $data['isi_laporan'] : '-';
    $tanggapan = isset($data['tanggapan']) ? $data['tanggapan'] : '-';
    $status = isset($data['status']) 
        ? ($data['status'] == 'proses' ? "Proses" : ($data['status'] == 'selesai' ? "Selesai" : "Menunggu")) 
        : 'Menunggu';

    // Tulis baris data
    echo "$no\t$tgl_tanggapan\t$nisn\t$judul\t$isi\t$tanggapan\t$status\n";
    $no++;
}

// Tutup koneksi database
mysqli_close($koneksi);
ob_end_flush();
exit();
?>
