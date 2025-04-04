<?php
session_start();
include '../layouts/header.php'; 

    include 'home.php';

?>

<!-- Modal Edit Pengaduan -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <input type="hidden" id="edit_id" name="id">
          <div class="mb-3">
            <label for="edit_judul" class="form-label">Judul</label>
            <input type="text" class="form-control" id="edit_judul" name="judul" required>
          </div>
          <div class="mb-3">
            <label for="edit_isi" class="form-label">Isi</label>
            <textarea class="form-control" id="edit_isi" name="isi" required></textarea>
          </div>
          <div class="mb-3">
            <label for="edit_tujuan" class="form-label">Tujuan Pengaduan</label>
            <input type="text" class="form-control" id="edit_tujuan" name="tujuan" required>
          </div>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../layouts/footer.php'; ?>

<!-- JavaScript untuk Modal Edit -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $(".editButton").click(function() {
    var id = $(this).data('id');
    var judul = $(this).data('judul');
    var isi = $(this).data('isi');
    var tujuan = $(this).data('tujuan');

    $("#edit_id").val(id);
    $("#edit_judul").val(judul);
    $("#edit_isi").val(isi);
    $("#edit_tujuan").val(tujuan);
    
    $("#editModal").modal('show');
  });

  $("#editForm").submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "../config/proses_edit.php",
      data: $(this).serialize(),
      dataType: "json",
      success: function(response) {
        if (response.status === 'success') {
          alert(response.message);
          $('#editModal').modal('hide');
          location.reload();
        } else {
          alert(response.message);
        }
      },
      error: function() {
        alert('Terjadi kesalahan saat mengedit data.');
      }
    });
  });
});
</script>
