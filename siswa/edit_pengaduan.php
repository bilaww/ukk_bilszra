<?php
include '../config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nisn = isset($_SESSION['nisn']) ? $_SESSION['nisn'] : '';

// Ambil ID Pengaduan dari URL
$id_pengaduan = isset($_GET['id_pengaduan']) ? $_GET['id_pengaduan'] : '';
if (!$id_pengaduan) {
    echo "<script>alert('ID pengaduan tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Ambil data pengaduan berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE id_pengaduan = '$id_pengaduan' AND nisn='$nisn'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Proses Update Pengaduan
if (isset($_POST['update'])) {
    $judul_laporan = $_POST['judul_laporan'];
    $isi_laporan = $_POST['isi_laporan'];
    $tujuan_pengaduan = $_POST['tujuan_pengaduan'];
    $foto_lama = $data['foto'];

    // Cek apakah pengguna mengunggah foto baru
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        $lokasi = '../assets/img/';
        $nama_foto = rand(0, 999) . '-' . basename($foto);

        if (!is_dir($lokasi)) {
            mkdir($lokasi, 0777, true);
        }

        if (move_uploaded_file($tmp, $lokasi . $nama_foto)) {
            // Hapus foto lama jika ada
            if (!empty($foto_lama) && is_file($lokasi . $foto_lama)) {
                unlink($lokasi . $foto_lama);
            }
        } else {
            echo "<script>alert('Gagal mengunggah foto!');</script>";
            exit;
        }
    } else {
        $nama_foto = $foto_lama; // Jika tidak ada foto baru, gunakan foto lama
    }

    // Update data ke database
    $update = mysqli_query($koneksi, "UPDATE pengaduan SET 
                                      judul_laporan='$judul_laporan', 
                                      isi_laporan='$isi_laporan', 
                                      tujuan_pengaduan='$tujuan_pengaduan', 
                                      foto='$nama_foto' 
                                      WHERE id_pengaduan='$id_pengaduan' AND nisn='$nisn'");

    if ($update) {
        echo "<script>
            alert('Data berhasil diperbarui!');
            window.location='index.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Pengaduan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card-header { background-color: #6c757d; color: #ffffff; }
    .btn-primary { background-color: #6c757d; border-color: #6c757d; }
    .btn-primary:hover { background-color: #5a6268; border-color: #545b62; }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-6 offset-lg-3">
        <div class="card shadow-sm border-0">
          <div class="card-header text-center">
            <h4 class="mb-0">Edit Pengaduan</h4>
          </div>
          <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label class="form-label fw-bold">Judul Laporan</label>
                <input type="text" class="form-control" name="judul_laporan" value="<?php echo htmlspecialchars($data['judul_laporan']); ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Isi Laporan</label>
                <textarea class="form-control" name="isi_laporan" rows="5" required><?php echo htmlspecialchars($data['isi_laporan']); ?></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Tujuan Pengaduan</label>
                <select class="form-control" name="tujuan_pengaduan" required>
                    <option value="BK" <?php echo ($data['tujuan_pengaduan'] == 'BK') ? 'selected' : ''; ?>>BK</option>
                    <option value="Humas" <?php echo ($data['tujuan_pengaduan'] == 'Humas') ? 'selected' : ''; ?>>Humas</option>
                    <option value="Prasarana" <?php echo ($data['tujuan_pengaduan'] == 'Prasarana') ? 'selected' : ''; ?>>Prasarana</option>
                    <option value="kurikulum" <?php echo ($data['tujuan_pengaduan'] == 'kurikulum') ? 'selected' : ''; ?>>Kurikulum</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Foto Saat Ini</label><br>
                <?php if (!empty($data['foto'])) { ?>
                  <img src="../assets/img/<?php echo htmlspecialchars($data['foto']); ?>" width="150" class="img-thumbnail">
                <?php } else { ?>
                  <p class="text-muted">Tidak ada foto</p>
                <?php } ?>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Ganti Foto (Opsional)</label>
                <input type="file" class="form-control" name="foto">
              </div>
              <div class="card-footer">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
