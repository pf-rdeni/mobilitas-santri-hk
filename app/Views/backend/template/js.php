<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Custom Scripts -->
<script>
// Base URL for AJAX requests
const baseUrl = '<?= rtrim(base_url(), '/') ?>';



$(document).ready(function() {

    // =================================================================
    // DARK MODE TOGGLE
    // =================================================================
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const body = document.body;
    
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    
    if (isDarkMode) {
        body.classList.add('dark-mode');
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
    }
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            body.classList.toggle('dark-mode');
            
            const isDark = body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark);
            
            if (isDark) {
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
            } else {
                darkModeIcon.classList.remove('fa-sun');
                darkModeIcon.classList.add('fa-moon');
            }
        });
    }
    
    // =================================================================
    // FLASH MESSAGES dengan SweetAlert2
    // =================================================================
    <?php if (session()->getFlashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        html: `<?= esc(session()->getFlashdata('success'), 'js') ?>`,
        timer: 3000,
        showConfirmButton: false
    });
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: `<?= esc(session()->getFlashdata('error'), 'js') ?>`,
        timer: 5000,
        showConfirmButton: true
    });
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('warning')): ?>
    Swal.fire({
        icon: 'warning',
        title: 'Perhatian!',
        text: `<?= esc(session()->getFlashdata('warning'), 'js') ?>`,
        timer: 4000,
        showConfirmButton: true
    });
    <?php endif; ?>
    
    // =================================================================
    // KONFIRMASI HAPUS
    // =================================================================
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const name = $(this).data('name') || 'item ini';
        
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data ' + name + ' akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
    
    // =================================================================
    // DATATABLES DEFAULT CONFIG
    // =================================================================
    if ($.fn.DataTable) {
        $.extend($.fn.dataTable.defaults, {
            language: {
                "sEmptyTable":   "Tidak ada data yang tersedia pada tabel ini",
                "sProcessing":   "Sedang memproses...",
                "sLengthMenu":   "Tampilkan _MENU_ entri",
                "sZeroRecords":  "Tidak ditemukan data yang sesuai",
                "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix":  "",
                "sSearch":       "Cari:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "Pertama",
                    "sPrevious": "Sebelumnya",
                    "sNext":     "Selanjutnya",
                    "sLast":     "Terakhir"
                }
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            responsive: true
        });
    }

    // =================================================================
    // INITIALIZATION COMPLETE
    // =================================================================
    console.log('Mobilitas Santri Backend initialized');
});

// Global function for delete confirmation
function confirmDelete(url, text = 'Data akan dihapus permanen!') {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
