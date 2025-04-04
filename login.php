
<div class="row justify-content-center mt-5">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header text-center bg-primary text-white rounded-top-4">
                <h2 class="fw-bold mb-0">Login</h2>
            </div>
            <div class="card-body p-4">
                <form action="config/aksi_login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-5">Email</label>
                        <input type="email" class="form-control p-3 fs-5" name="email" placeholder="Masukkan Email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-5">Password</label>
                        <input type="password" class="form-control p-3 fs-5" name="password" placeholder="Masukkan Password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-5">Login Sebagai</label>
                        <select class="form-control p-3 fs-5" name="level">
                            <option value="siswa">Siswa</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="kirim" class="btn btn-lg btn-primary fw-bold p-3 fs-4">Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center bg-light rounded-bottom-4 p-3">
    <a href="index.php?page=registrasi" class="text-decoration-none text-primary fw-semibold fs-5">Belum punya akun? Daftar sebagai siswa di sini!</a>
    <br>
    <a href="index.php?page=registrasi_petugas" class="text-decoration-none text-danger fw-semibold fs-5">Petugas? Daftar di sini!</a>
</div>

        </div>
    </div>    
</div>
