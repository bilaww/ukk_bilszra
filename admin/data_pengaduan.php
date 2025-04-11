<?php
include '../config/koneksi.php'; // Pastikan koneksi benar

// PROSES STATUS DAN TANGGAPAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengaduan = $_POST['id_pengaduan'] ?? '';
    $action = $_POST['action'] ?? '';

    if (empty($id_pengaduan) || empty($action)) {
        echo "<script>alert('Data tidak valid!'); window.history.back();</script>";
        exit;
    }

    switch ($action) {
        case 'approve':
            $newStatus = 'Proses';
            break;

        case 'tolak':
            $newStatus = 'Tolak';
            break;

        case 'selesai':
            $tanggapan = $_POST['tanggapan'] ?? '';

            $insertTanggapan = "INSERT INTO tanggapan (id_pengaduan, tanggapan, tgl_tanggapan) VALUES (?, ?, NOW())";
            $stmt_tanggapan = mysqli_prepare($koneksi, $insertTanggapan);
            mysqli_stmt_bind_param($stmt_tanggapan, "is", $id_pengaduan, $tanggapan);

            if (mysqli_stmt_execute($stmt_tanggapan)) {
                $updateStatus = "UPDATE pengaduan SET status = 'Selesai' WHERE id_pengaduan = ?";
                $stmt_update = mysqli_prepare($koneksi, $updateStatus);
                mysqli_stmt_bind_param($stmt_update, "i", $id_pengaduan);
                mysqli_stmt_execute($stmt_update);

                echo "<script>alert('Tanggapan berhasil disimpan dan status diperbarui!'); window.location.href='index.php?page=pengaduan';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan tanggapan: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
            }
            exit;

        default:
            echo "<script>alert('Aksi tidak valid!'); window.history.back();</script>";
            exit;
    }

    // Update status jika bukan aksi selesai
    $updateQuery = "UPDATE pengaduan SET status = ? WHERE id_pengaduan = ?";
    $stmt = mysqli_prepare($koneksi, $updateQuery);
    mysqli_stmt_bind_param($stmt, "si", $newStatus, $id_pengaduan);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Status berhasil diperbarui menjadi $newStatus'); window.location.href='index.php?page=pengaduan';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui status: " . mysqli_stmt_error($stmt) . "');</script>";
    }
    exit;
}

// PROSES HAPUS DATA PENGADUAN
if (isset($_GET['id'])) {
    $id_pengaduan = $_GET['id'];

    $cek = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE id_pengaduan = '$id_pengaduan'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($koneksi, "DELETE FROM tanggapan WHERE id_pengaduan = '$id_pengaduan'");
        $stmt = mysqli_prepare($koneksi, "DELETE FROM pengaduan WHERE id_pengaduan = ?");
        mysqli_stmt_bind_param($stmt, "i", $id_pengaduan);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Pengaduan berhasil dihapus!'); window.location.href='index.php?page=pengaduan';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($koneksi) . "');</script>";
        }
    } else {
        echo "<script>alert('Data pengaduan tidak ditemukan!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-header bg-secondary text-white d-flex justify-content-between">
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
// Pagination
$limit = 5;
$page = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
$offset = ($page - 1) * $limit;

$result_total = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengaduan WHERE status != 'Selesai'");
$row_total = mysqli_fetch_assoc($result_total);
$total_data = $row_total['total'];
$total_pages = ceil($total_data / $limit);

$no = $offset + 1;
$query = mysqli_query($koneksi, "SELECT a.*, b.nama FROM pengaduan a INNER JOIN siswa b ON a.nisn = b.nisn WHERE a.status != 'Selesai' ORDER BY a.id_pengaduan DESC LIMIT $limit OFFSET $offset");
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
                case 'Tolak':
                    echo '<span class="badge bg-danger">Ditolak</span>';
                    break;
                default:
                    echo '<span class="badge bg-secondary">Menunggu</span>';
            }
            ?>
        </td>
        <td>
            <form method="POST" action="" style="display:inline;">
                <input type="hidden" name="id_pengaduan" value="<?php echo $data['id_pengaduan']; ?>">
                <button type="submit" name="action" value="approve" class="btn btn-sm btn-warning mb-1">Verifikasi</button>
            </form>

            <form method="POST" action="" style="display:inline;">
                <input type="hidden" name="id_pengaduan" value="<?php echo $data['id_pengaduan']; ?>">
                <button type="submit" name="action" value="tolak" class="btn btn-sm btn-secondary mb-1">Tolak</button>
            </form>

            <form method="POST" action="" style="display:inline;">
                <input type="hidden" name="id_pengaduan" value="<?php echo $data['id_pengaduan']; ?>">
                <input type="hidden" name="tanggapan" value="Pengaduan ditanggapi dan telah selesai.">
                <button type="submit" name="action" value="selesai" class="btn btn-sm btn-success mb-1">Selesai</button>
            </form>

            <a href="?id=<?php echo $data['id_pengaduan']; ?>" onclick="return confirm('Yakin ingin menghapus pengaduan ini?')" class="btn btn-sm btn-danger">Hapus</a>
        </td>
    </tr>
<?php } ?>
                </tbody>
            </table>

            <!-- PAGINATION -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=pengaduan&page_no=<?php echo $page - 1; ?>">Sebelumnya</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=pengaduan&page_no=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=pengaduan&page_no=<?php echo $page + 1; ?>">Berikutnya</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
