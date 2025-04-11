<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-primary text-white">
                <h4>Registrasi</h4>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">NISN</label>
                        <input type="number" class="form-control" name="nisn" placeholder="Masukkan NISN" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Masukkan Email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Masukkan Password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No.Telp</label>
                        <input type="number" class="form-control" name="telp" placeholder="Masukkan No.Telp" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="kirim" class="btn btn-primary">Daftar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="index.php?page=login" class="text-decoration-none">Sudah punya akun? Login di sini!</a>
            </div>
        </div>
    </div>    
</div>

<?php
include 'config/koneksi.php';

if (isset($_POST['kirim'])) {
    // Gunakan mysqli_real_escape_string untuk aman dari karakter spesial
    $nisn = mysqli_real_escape_string($koneksi, trim($_POST['nisn']));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $email = mysqli_real_escape_string($koneksi, strtolower(trim($_POST['email'])));
    $password_raw = trim($_POST['password']);
    $password = mysqli_real_escape_string($koneksi, password_hash($password_raw, PASSWORD_DEFAULT));
    $telp = mysqli_real_escape_string($koneksi, trim($_POST['telp']));
    $level = 'siswa';

    // Cek apakah NISN atau Email sudah terdaftar
    $cek_query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nisn='$nisn' OR email='$email'");

    if (mysqli_num_rows($cek_query) > 0) {
        echo "<script>alert('NISN atau Email sudah terdaftar!'); window.location='index.php';</script>";
    } else {
        // Simpan ke database
        $query = mysqli_query($koneksi, "INSERT INTO siswa (nisn, nama, email, password, telp, level) 
                                         VALUES ('$nisn', '$nama', '$email', '$password', '$telp', '$level')");

        if ($query) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='index.php?page=login';</script>";
        } else {
            echo "<script>alert('Registrasi gagal! Silakan coba lagi.'); window.location='index.php';</script>";
        }
    }
}
?>
