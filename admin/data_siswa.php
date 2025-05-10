<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ukk_bilszra";

$koneksi = mysqli_connect($servername, $username, $password, $dbname);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Hapus data siswa
if (isset($_POST['hapus_siswa'])) {
    $nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $query = mysqli_query($koneksi, "DELETE FROM siswa WHERE nisn='$nisn'");
    if ($query) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='http://localhost/UKK_BILSZRA/admin/index.php?page=siswa';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus!');</script>";
    }
}

// Pagination setup
$batas = 5;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next = $halaman + 1;

$data = mysqli_query($koneksi, "SELECT * FROM siswa");
$jumlah_data = mysqli_num_rows($data);
$total_halaman = ceil($jumlah_data / $batas);

$data_siswa = mysqli_query($koneksi, "SELECT * FROM siswa LIMIT $halaman_awal, $batas");
$no = $halaman_awal + 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Pengaduan Siswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Website Pengaduan Siswa</h2>

        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahData">Tambah Data Siswa</a>

        <!-- Modal Tambah Data -->
        <div class="modal fade" id="tambahData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nisn" class="form-label">NISN</label>
                                <input type="text" name="nisn" class="form-control" placeholder="Masukkan NISN" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
                            </div>
                            <div class="mb-3">
                                <label for="telp" class="form-label">No. Telepon</label>
                                <input type="text" name="telp" class="form-control" placeholder="Masukkan No. Telepon" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Siswa -->
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">DATA SISWA</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>NO</th>
                                    <th>NISN</th>
                                    <th>NAMA</th>
                                    <th>EMAIL</th>
                                    <th>NO. TELEPON</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($data_siswa) > 0) {
                                    while ($data = mysqli_fetch_array($data_siswa)) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $data['nisn']; ?></td>
                                            <td><?= $data['nama']; ?></td>
                                            <td><?= $data['email']; ?></td>
                                            <td><?= $data['telp']; ?></td>
                                            <td>
                                                <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?= $data['nisn']; ?>">HAPUS</a>
                                                <!-- Modal Hapus -->
                                                <div class="modal fade" id="hapus<?= $data['nisn']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Hapus Data</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="" method="POST">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="nisn" value="<?= $data['nisn']; ?>">
                                                                    <p>Apakah yakin akan menghapus data <strong><?= $data['nama']; ?></strong>?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" name="hapus_siswa" class="btn btn-danger">Hapus</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Data siswa tidak ditemukan.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= ($halaman <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=siswa&halaman=<?= $previous ?>">«</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                                    <li class="page-item <?= ($halaman == $i) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=siswa&halaman=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=siswa&halaman=<?= $next ?>">»</a>
                                </li>
                            </ul>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
