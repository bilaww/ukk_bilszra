<?php
include '../config/koneksi.php';

// Tambah Data Petugas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_petugas'])) {
    $nama_petugas = $_POST['nama_petugas'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telp = $_POST['telp'];

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO petugas (nama_petugas, email, password, telp) VALUES ('$nama_petugas', '$email', '$password_hashed', '$telp')";
        if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Petugas berhasil ditambahkan!'); window.location='index.php?page=petugas';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan petugas!');</script>";
    }
}

// Hapus Data Petugas
if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    $query = "DELETE FROM petugas WHERE id_petugas = '$id'";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Petugas berhasil dihapus!'); window.location='index.php?page=petugas';</script>";
    } else {
        echo "<script>alert('Gagal menghapus petugas!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Petugas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Data Petugas</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPetugasModal">Tambah Data Petugas</button>
        <div class="card">
            <div class="card-header bg-secondary text-white">DATA PETUGAS</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Petugas</th>
                            <th>Nama Petugas</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM petugas");
                        while ($data = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $data['id_petugas']; ?></td>
                            <td><?= isset($data['nama_petugas']) ? $data['nama_petugas'] : '<i>Nama tidak tersedia</i>'; ?></td>
                            <td><?= $data['email']; ?></td>
                            <td><?= $data['telp']; ?></td>
                            <td>
                                <a href="index.php?page=petugas&hapus_id=<?= $data['id_petugas']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus petugas ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Petugas -->
    <div class="modal fade" id="tambahPetugasModal" tabindex="-1" aria-labelledby="tambahPetugasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPetugasModalLabel">Tambah Data Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php?page=petugas" method="POST">
                        <div class="mb-3">
                            <label for="nama_petugas" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_petugas" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
                        </div>
                        <div class="mb-3">
                            <label for="telp" class="form-label">Telp</label>
                            <input type="number" class="form-control" name="telp" placeholder="Masukkan nomor telepon" required>
                        </div>
                        <button type="submit" name="tambah_petugas" class="btn btn-primary">Tambah Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
