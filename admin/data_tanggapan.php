<div class="container">
  <div class="row mt-5">
    <div class="col-lg-12">
      <div class="card shadow border-0">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">DATA TANGGAPAN</h5>
          <a href="/UKK_BILSZRA/admin/export_tanggapan.php" class="btn btn-success" target="_blank">
            Export Excel
          </a>
        </div>
        <div class="card-body">
          <table class="table table-hover table-bordered">
            <thead class="table-primary text-center">
              <tr>
                <th>NO</th>
                <th>TANGGAL</th>
                <th>NISN</th>
                <th>JUDUL LAPORAN</th>
                <th>ISI LAPORAN</th>
                <th>TANGGAPAN</th>
                <th>STATUS</th>
                <th>AKSI</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include '../config/koneksi.php';
              $no = 1;
              $query = mysqli_query($koneksi, "SELECT a.*, b.* FROM tanggapan a 
                                 INNER JOIN pengaduan b ON a.id_pengaduan = b.id_pengaduan 
                                 ORDER BY a.tgl_tanggapan DESC");

              while ($data = mysqli_fetch_array($query)) { ?>
                <tr class="text-center align-middle">
                  <td><?php echo $no++; ?></td>
                  <td><?php echo $data['tgl_tanggapan']; ?></td>
                  <td><?php echo $data['nisn']; ?></td>
                  <td><?php echo $data['judul_laporan']; ?></td>
                  <td><?php echo $data['isi_laporan']; ?></td>
                  <td><?php echo $data['tanggapan']; ?></td>
                  <td><?php echo $data['status']; ?></td>
                  <td>
                    <?php if ($data['status'] === 'proses') { ?>
                      <button class="btn btn-primary" onclick="tanggapiData(<?php echo $data['id_pengaduan']; ?>)">TANGGAPI</button>
                    <?php } ?>
                    <button class="btn btn-danger" onclick="hapusData(<?php echo $data['id_tanggapan']; ?>)">HAPUS</button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tanggapi -->
<div class="modal fade" id="modalTanggapi" tabindex="-1" aria-labelledby="modalTanggapiLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTanggapiLabel">Tanggapi Laporan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="">
        <div class="modal-body">
          <input type="hidden" id="id_pengaduan" name="id_pengaduan">
          <div class="form-group">
            <label for="isi_tanggapan">Isi Tanggapan</label>
            <textarea class="form-control" id="isi_tanggapan" name="isi_tanggapan" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" name="kirim" class="btn btn-primary">Kirim</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
if (isset($_POST['kirim'])) {
  $id_pengaduan = $_POST['id_pengaduan'];
  $isi_tanggapan = $_POST['isi_tanggapan'];
  $tgl_tanggapan = date("Y-m-d");
  $status = "selesai";

  $insert_tanggapan = mysqli_query($koneksi, "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan) VALUES ('$id_pengaduan', '$tgl_tanggapan', '$isi_tanggapan')");
  $update_status = mysqli_query($koneksi, "UPDATE pengaduan SET status='$status' WHERE id_pengaduan='$id_pengaduan'");

  if ($insert_tanggapan && $update_status) {
    echo "<script>
      Swal.fire({
        title: 'Berhasil!',
        text: 'Tanggapan berhasil dikirim dan status diperbarui.',
        icon: 'success'
      }).then(() => window.location.href = 'index.php?page=pengaduan');
    </script>";
  } else {
    echo "<script>alert('Gagal mengirim tanggapan!');</script>";
  }
}

if (isset($_GET['hapus'])) {
  $id_tanggapan = $_GET['hapus'];
  $hapus_tanggapan = mysqli_query($koneksi, "DELETE FROM tanggapan WHERE id_tanggapan='$id_tanggapan'");

  if ($hapus_tanggapan) {
    echo "<script>
      Swal.fire({
        title: 'Berhasil!',
        text: 'Data tanggapan berhasil dihapus.',
        icon: 'success'
      }).then(() => window.location.href = 'index.php?page=tanggapan');
    </script>";
  } else {
    echo "<script>alert('Gagal menghapus data!');</script>";
  }
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  function tanggapiData(id_pengaduan) {
    document.getElementById('id_pengaduan').value = id_pengaduan;
    new bootstrap.Modal(document.getElementById('modalTanggapi')).show();
  }

  function hapusData(id) {
    Swal.fire({
      title: "Yakin ingin menghapus?",
      text: "Data akan dihapus secara permanen!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Ya, hapus!",
      cancelButtonText: "Batal"
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "index.php?page=tanggapan&hapus=" + id;
      }
    });
  }
</script>
