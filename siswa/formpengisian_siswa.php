
<?php

// Menampilkan pesan sukses jika data berhasil diperbarui
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('Data berhasil diperbarui!');</script>";
}
include '../config/koneksi.php';

if (isset($_POST['update_status'])) {
    $pengaduan_id = $_POST['pengaduan_id'];
    $status = $_POST['status'];

    $query = "UPDATE pengaduan SET status='$status' WHERE id='$pengaduan_id'";
    if (mysqli_query($conn, $query)) {
        // Ambil email siswa berdasarkan pengaduan_id
        $result = mysqli_query($conn, "SELECT s.email FROM siswa s JOIN pengaduan p ON s.id = p.siswa_id WHERE p.id = '$pengaduan_id'");
        $row = mysqli_fetch_assoc($result);
        $email_siswa = $row['email'];

        // Kirim email ke siswa
        $subject = "Update Status Pengaduan";
        $message = "Status pengaduanmu telah diperbarui menjadi: $status";
        kirimEmail($email_siswa, $subject, $message);

        echo "Status berhasil diperbarui dan email telah dikirim!";
    }
}

// Proses Verifikasi dan Tanggapan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pengaduan'])) {
    $id_pengaduan = mysqli_real_escape_string($koneksi, $_POST['id_pengaduan']);

    if (!is_numeric($id_pengaduan)) {
        echo "<script>alert('ID pengaduan tidak valid!');</script>";
        exit;
    }

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'approve') {
            $newStatus = 'proses';
        } elseif ($action == 'tolak') {
            $newStatus = 'tolak';
        } elseif ($action == 'selesai' && isset($_POST['tanggapan'])) {
            $tanggapan = mysqli_real_escape_string($koneksi, $_POST['tanggapan']);
            $insertTanggapan = "INSERT INTO tanggapan (id_pengaduan, tanggapan, tgl_tanggapan) VALUES ('$id_pengaduan', '$tanggapan', NOW())";
            mysqli_query($koneksi, $insertTanggapan);
            $newStatus = 'selesai';
        } else {
            echo "<script>alert('Aksi tidak valid!');</script>";
            exit;
        }

        $updateQuery = "UPDATE pengaduan SET status = '$newStatus' WHERE id_pengaduan = '$id_pengaduan'";
        if (mysqli_query($koneksi, $updateQuery)) {
            header("Location: index.php?page=pengaduan&success=1");
            exit;
        } else {
            echo "Terjadi kesalahan saat memperbarui data: " . mysqli_error($koneksi);
        }
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
                    while ($data = mysqli_fetch_array($query)) { ?>
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
                                if ($data['status'] == 'proses') {
                                    echo '<span class="badge bg-warning">Proses</span>';
                                } elseif ($data['status'] == 'selesai') {
                                    echo '<span class="badge bg-success">Selesai</span>';
                                } elseif ($data['status'] == 'tolak') {
                                    echo '<span class="badge bg-danger">Ditolak</span>';
                                } else {
                                    echo '<span class="badge bg-secondary">Menunggu</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#verifikasiModal<?php echo $data['id_pengaduan']; ?>">Verifikasi</button>
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tanggapiModal<?php echo $data['id_pengaduan']; ?>">Tanggapi</button>
                                    <a href="hapus.php?id=<?php echo $data['id_pengaduan']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?');" class="btn btn-danger btn-sm">Hapus</a>
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


