<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';
$nisn = isset($_SESSION['nisn']) ? $_SESSION['nisn'] : ''; 

$tanggal = date("Y-m-d");

// Proses Kirim Pengaduan
if (isset($_POST['kirim'])) {
    $judul_laporan = $_POST['judul_laporan'];
    $isi_laporan = $_POST['isi_laporan'];
    $tujuan_pengaduan = $_POST['tujuan_pengaduan']; 
    $status = 'Menunggu';
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $lokasi = '../assets/img/';
    $nama_foto = rand(0, 999) . '-' . basename($foto);

    if (!is_dir($lokasi)) {
        mkdir($lokasi, 0777, true);
    }

    if (move_uploaded_file($tmp, $lokasi . $nama_foto)) {
        $query = mysqli_query($koneksi, "INSERT INTO pengaduan (tgl_pengaduan, nisn, judul_laporan, isi_laporan, tujuan_pengaduan, foto, status) 
                                         VALUES ('$tanggal', '$nisn', '$judul_laporan', '$isi_laporan', '$tujuan_pengaduan', '$nama_foto', '$status')");

        echo "<script>
        alert('Data berhasil dikirim!');
        window.location='index.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Gagal mengunggah foto!');</script>";
    }
}

// Proses Hapus Pengaduan
if (isset($_POST['hapus'])) {
    $id_pengaduan = $_POST['id_pengaduan'];

    $queryFile = mysqli_query($koneksi, "SELECT foto FROM pengaduan WHERE id_pengaduan = '$id_pengaduan'");
    $dataFile = mysqli_fetch_assoc($queryFile);
    $foto = $dataFile['foto'];

    if (!empty($foto) && is_file('../assets/img/' . $foto)) {
        unlink('../assets/img/' . $foto);
    }

    $query = mysqli_query($koneksi, "DELETE FROM pengaduan WHERE id_pengaduan = '$id_pengaduan'");

    if ($query) {
        echo "<script>
        alert('Pengaduan berhasil dihapus!');
        window.location='index.php';
        </script>";
    } else {
        echo "<script>alert('Gagal menghapus pengaduan!'); window.location='index.php';</script>";
    }
}

// PAGINATION
$batas = 5;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengaduan WHERE nisn='$nisn'");
$data_total = mysqli_fetch_assoc($result);
$jumlah_data = $data_total['total'];
$total_halaman = ceil($jumlah_data / $batas);

$query = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE nisn='$nisn' ORDER BY id_pengaduan DESC LIMIT $halaman_awal, $batas");
$no = $halaman_awal + 1;
?>

<!DOCTYPE html>
<html lang="id_pengaduan">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Pengaduan Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card-header { background-color: #6c757d; color: #ffffff; }
    .btn-primary { background-color: #6c757d; border-color: #6c757d; }
    .btn-primary:hover { background-color: #5a6268; border-color: #545b62; }
    .table-primary { background-color: #e9ecef; }
    .table-hover tbody tr:hover { background-color: #dee2e6; }
    .img-thumbnail { border-radius: 0.5rem; }
    h1, h4, h5 { color: #343a40; }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="text-center mb-4">
      <h1 class="fw-bold">Selamat Datang <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Guest'; ?></h1>
    </div>

    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <div class="card shadow-sm border-0">
          <div class="card-header text-center">
            <h4 class="mb-0">FORM PENGADUAN</h4>
          </div>
          <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label class="form-label fw-bold">Judul Laporan</label>
                <input type="text" class="form-control" name="judul_laporan" placeholder="Masukkan Judul" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Isi Laporan</label>
                <textarea class="form-control" name="isi_laporan" placeholder="Masukkan Isi Laporan" rows="5" required></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Tujuan Pengaduan</label>
                <select class="form-control" name="tujuan_pengaduan" required>
                    <option value="BK">BK</option>
                    <option value="Humas">Humas</option>
                    <option value="Prasarana">Prasarana</option>
                    <option value="Kurikulum">Kurikulum</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Foto</label>
                <input type="file" class="form-control" name="foto" required>
              </div>
              <div class="card-footer">
                <button type="submit" name="kirim" class="btn btn-primary">KIRIM</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Pengaduan -->
    <div class="row mt-5">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0">
          <div class="card-header">
            <h5 class="mb-0">DAFTAR PENGADUAN</h5>
          </div>
          <div class="card-body">
            <table class="table table-hover table-bordered">
              <thead class="table-primary text-center">
                <tr>
                  <th>NO</th>
                  <th>JUDUL</th>
                  <th>ISI</th>
                  <th>Tujuan Pengaduan</th>
                  <th>FOTO</th>
                  <th>STATUS</th>
                  <th>AKSI</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($data = mysqli_fetch_array($query)) { ?>
                  <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $data['judul_laporan']; ?></td>
                    <td><?php echo $data['isi_laporan']; ?></td>
                    <td><?php echo htmlspecialchars($data['tujuan_pengaduan']); ?></td> 
                    <td>
                      <?php if (!empty($data['foto'])) { ?>
                        <img src="../assets/img/<?php echo htmlspecialchars($data['foto']); ?>" width="100" class="img-thumbnail">
                      <?php } else { ?>
                        <span class="text-muted">Tidak ada foto</span>
                      <?php } ?>
                    </td>
                    <td>
                      <?php
                        $status = $data['status'];
                        $badge_class = 'bg-secondary';
                        if ($status == 'Proses') {
                            $badge_class = 'bg-warning';
                        } elseif ($status == 'Selesai') {
                            $badge_class = 'bg-success';
                        } elseif ($status == 'Ditolak') {
                            $badge_class = 'bg-danger';
                        }
                        echo "<span class='badge $badge_class'>" . htmlspecialchars($status) . "</span>";
                      ?>
                    </td>
                    <td>
                      <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="id_pengaduan" value="<?php echo $data['id_pengaduan']; ?>">
                        <button type="submit" name="hapus" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?');">Hapus</button>
                      </form>
                      <a href="edit_pengaduan.php?id_pengaduan=<?php echo $data['id_pengaduan']; ?>" class="btn btn-warning">Edit</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>

            <!-- Navigasi Pagination -->
            <nav>
              <ul class="pagination justify-content-center">
                <?php if ($halaman > 1): ?>
                  <li class="page-item"><a class="page-link" href="?halaman=<?php echo $halaman - 1; ?>">«</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                  <li class="page-item <?php echo ($i == $halaman) ? 'active' : ''; ?>">
                    <a class="page-link" href="?halaman=<?php echo $i; ?>"><?php echo $i; ?></a>
                  </li>
                <?php endfor; ?>

                <?php if ($halaman < $total_halaman): ?>
                  <li class="page-item"><a class="page-link" href="?halaman=<?php echo $halaman + 1; ?>">»</a></li>
                <?php endif; ?>
              </ul>
            </nav>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
