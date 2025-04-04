<?php
include '../config/koneksi.php'; // Pastikan koneksi benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengaduan = $_POST['id_pengaduan'] ?? '';
    $action = $_POST['action'] ?? '';

    if (empty($id_pengaduan) || empty($action)) {
        echo "<script>alert('Data tidak valid!'); window.history.back();</script>";
        exit;
    }

    // Tentukan status baru berdasarkan aksi yang diterima
    switch ($action) {
        case 'approve':
            $newStatus = 'Proses';
            break;
        case 'tolak':
            $newStatus = 'Tolak';
            break;
        case 'selesai':
            if (!isset($_POST['tanggapan']) || empty(trim($_POST['tanggapan']))) {
                echo "<script>alert('Tanggapan tidak boleh kosong!'); window.history.back();</script>";
                exit;
            }

            $tanggapan = $_POST['tanggapan'];
            
            // Insert tanggapan ke database
            $insertTanggapan = "INSERT INTO tanggapan (id_pengaduan, tanggapan, tgl_tanggapan) VALUES (?, ?, NOW())";
            $stmt_tanggapan = mysqli_prepare($koneksi, $insertTanggapan);
            mysqli_stmt_bind_param($stmt_tanggapan, "is", $id_pengaduan, $tanggapan);
            mysqli_stmt_execute($stmt_tanggapan);

            if (mysqli_stmt_affected_rows($stmt_tanggapan) > 0) {
                $newStatus = 'Selesai';
            } else {
                echo "<script>alert('Gagal menambahkan tanggapan: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
                exit;
            }
            break;
        default:
            echo "<script>alert('Aksi tidak valid!'); window.history.back();</script>";
            exit;
    }

    // Update status pengaduan
    $updateQuery = "UPDATE pengaduan SET status = ? WHERE id_pengaduan = ?";
    $stmt = mysqli_prepare($koneksi, $updateQuery);
    mysqli_stmt_bind_param($stmt, "si", $newStatus, $id_pengaduan);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Status berhasil diperbarui menjadi $newStatus'); window.location.href='index.php?page=pengaduan';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui status: " . mysqli_stmt_error($stmt) . "');</script>";
    }
}

// PROSES HAPUS DATA PENGADUAN
if (isset($_GET['id'])) {
    $id_pengaduan = $_GET['id'];
    
    // Pastikan data ada sebelum dihapus
    $cek = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE id_pengaduan = '$id_pengaduan'");
    if (mysqli_num_rows($cek) > 0) {
        // Hapus tanggapan terkait sebelum menghapus pengaduan
        mysqli_query($koneksi, "DELETE FROM tanggapan WHERE id_pengaduan = '$id_pengaduan'");

        $deleteQuery = "DELETE FROM pengaduan WHERE id_pengaduan = ?";
        $stmt = mysqli_prepare($koneksi, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $id_pengaduan);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Pengaduan berhasil dihapus!'); window.location.href='index.php?page=pengaduan';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($koneksi) . "');</script>";
        }
    } else {
        echo "<script>alert('Data tidak ditemukan!');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">DATA PENGADUAN</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered table-responsive">
                <thead class="table-primary text-center">
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL</th>
                        <th>NAMA</th>
                        <th>JUDUL</th>
                        <th>LAPORAN</th>
                        <th>FOTO</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT a.*, b.nama FROM pengaduan a INNER JOIN siswa b ON a.nisn = b.nisn ORDER BY a.id_pengaduan DESC");
                    while ($data = mysqli_fetch_assoc($query)) { ?>
                        <tr class="text-center align-middle">
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $data['tgl_pengaduan']; ?></td>
                            <td><?php echo $data['nama']; ?></td>
                            <td><?php echo $data['judul_laporan']; ?></td>
                            <td><?php echo $data['isi_laporan']; ?></td>
                            <td>
                                <?php if (!empty($data['foto']) && file_exists("../assets/img/" . $data['foto'])) { ?>
                                    <img src="../assets/img/<?php echo $data['foto']; ?>" width="100">
                                <?php } else { ?>
                                    <span>Tidak ada foto</span>
                                <?php } ?>
                            </td>
                            <td>
    <?php
    switch ($data['status']) {
        case 'Proses':
            echo '<span class="badge bg-warning">Proses</span>';
            break;
        case 'Selesai':
            echo '<span class="badge bg-success">Selesai</span>';
            break;
        case 'Tolak':
            echo '<span class="badge bg-danger">Ditolak</span>';
            break;
        default:
            echo '<span class="badge bg-secondary">Menunggu</span>';
    }
    ?>
</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#verifikasiModal<?php echo $data['id_pengaduan']; ?>">Verifikasi</button>
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tanggapiModal<?php echo $data['id_pengaduan']; ?>">Tanggapi</button>
                                    <a href="data_pengaduan.php?id=<?php echo $data['id_pengaduan']; ?>&action=hapus"
   onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?');" 
   class="btn btn-danger btn-sm">Hapus</a>


                                </div>
                            </td>
                        </tr>

                       <!-- MODAL VERIFIKASI -->
<div class="modal fade" id="verifikasiModal<?php echo $data['id_pengaduan']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi: <?php echo $data['judul_laporan']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="id_pengaduan" value="<?php echo $data['id_pengaduan']; ?>">
                <div class="modal-body">
                    <p>Pilih status pengaduan:</p>
                    <button type="submit" name="action" value="approve" class="btn btn-warning">Proses</button>
                    <button type="submit" name="action" value="tolak" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

                        <!-- MODAL TANGGAPI -->
                        <div class="modal fade" id="tanggapiModal<?php echo $data['id_pengaduan']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tanggapan: <?php echo $data['judul_laporan']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id_pengaduan" value="<?php echo $data['id_pengaduan']; ?>">
                                        <div class="modal-body">
                                            <textarea name="tanggapan" class="form-control" placeholder="Masukkan tanggapan"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="action" value="selesai" class="btn btn-success">Selesai</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
