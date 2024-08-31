<div class="footer-wrap pd-20 mb-20 card-box">
    Powered By <a href="#" target="_blank">Indah</a>
</div>
</div>
</div>
<!-- js -->
<script src="../vendors/scripts/core.js" defer></script>
<script src="../vendors/scripts/script.min.js" defer></script>
<script src="../vendors/scripts/process.js" defer></script>
<script src="../vendors/scripts/layout-settings.js" defer></script>
<script src="../src/plugins/cropperjs/dist/cropper.js"></script>
<script src="../src/plugins/datatables/js/jquery.dataTables.min.js" defer></script>
<script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js" defer></script>
<script src="../src/plugins/datatables/js/dataTables.responsive.min.js" defer></script>
<script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js" defer></script>
<!-- buttons for Export datatable -->
<script src="../src/plugins/datatables/js/dataTables.buttons.min.js" defer></script>
<script src="../src/plugins/datatables/js/buttons.bootstrap4.min.js" defer></script>
<script src="../src/plugins/datatables/js/buttons.print.min.js" defer></script>
<script src="../src/plugins/datatables/js/buttons.html5.min.js" defer></script>
<script src="../src/plugins/datatables/js/buttons.flash.min.js" defer></script>
<script src="../src/plugins/datatables/js/pdfmake.min.js" defer></script>
<script src="../src/plugins/datatables/js/vfs_fonts.js" defer></script>
<script src="src/plugins/fullcalendar/fullcalendar.min.js"></script>
<!-- Datatable Setting js -->
<script src="../vendors/scripts/datatable-setting.js" defer></script>

<script src="src/plugins/sweetalert2/sweetalert2.all.js" defer></script>
<script src="src/plugins/sweetalert2/sweet-alert.init.js" defer></script>

<!-- Skrip untuk logout, pastikan tidak duplikat -->
<script>
    document.getElementById('logoutButton').addEventListener('click', function(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan keluar dari sesi ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, keluar',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../fungsi/logout.php';
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Fungsi untuk format IDR
    function formatIDR(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(number);
    }

    // Event listener untuk modal sortir
    $('#sortirModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var nama_pengirim = button.data('nama');
        var no_hp = button.data('nohp');
        var tujuan = button.data('tujuan');
        var berat = button.data('berat');
        var jumlah = button.data('jumlah');
        var tanggal = button.data('tanggal');
        var biaya = button.data('biaya');
        var total = button.data('total');
        var sttsbrg = button.data('sttsbrg');
        var status = button.data('status');

        var modal = $(this);
        modal.find('.modal-body #edit_id').val(id);
        modal.find('.modal-body #edit_nama_pengirim').val(nama_pengirim);
        modal.find('.modal-body #edit_no_hp').val(no_hp);
        modal.find('.modal-body #edit_tujuan').val(tujuan);
        modal.find('.modal-body #edit_berat').val(berat);
        modal.find('.modal-body #edit_jumlah_paket').val(jumlah);
        modal.find('.modal-body #edit_tgl_pengirim').val(new Date(tanggal).toISOString().slice(0, 16));
        modal.find('.modal-body #edit_biaya_paket').val(formatIDR(biaya)).data('original-value', biaya);
        modal.find('.modal-body #edit_total_biaya').val(formatIDR(total)).data('original-value', total);
        modal.find('.modal-body #edit_sttsbrg').val(sttsbrg);
        modal.find('.modal-body #edit_status').val(status);
    });

    // Sebelum submit, ubah format IDR kembali ke angka
    $('#sortirForm').on('submit', function () {
        $('#edit_biaya_paket').val($('#edit_biaya_paket').data('original-value'));
        $('#edit_total_biaya').val($('#edit_total_biaya').data('original-value'));
    });
});
</script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect atau kirimkan permintaan POST untuk menghapus data
                window.location.href = 'dtasortir.php?action=delete&id=' + id;
            }
        });
    }
</script>
</body>
</html>
