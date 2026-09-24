{{-- ================= SWEETALERT GLOBAL ================= --}}

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                timer: 3000,
                showConfirmButton: true,
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: '❌ Gagal!',
                text: @json(session('error')),
                timer: 4000,
                showConfirmButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if(session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'info',
                title: 'ℹ️ Informasi',
                text: @json(session('info')),
                timer: 3000,
                showConfirmButton: true,
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: '⚠️ Peringatan',
                text: @json(session('warning')),
                timer: 3000,
                showConfirmButton: true,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if(isset($errors) && $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessages = @json($errors->all());
            Swal.fire({
                icon: 'error',
                title: '⚠️ Validasi Gagal!',
                html: '<ul style="text-align:left;margin:0;padding-left:1.25rem;">' +
                      errorMessages.map(function(msg) {
                          return '<li>' + msg + '</li>';
                      }).join('') +
                      '</ul>',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif