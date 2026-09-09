@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
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
                text: {!! json_encode(session('error')) !!},
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
                text: {!! json_encode(session('info')) !!},
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
                text: {!! json_encode(session('warning')) !!},
                timer: 3000,
                showConfirmButton: true,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let errorMessages = '';
            @foreach($errors->all() as $error)
                errorMessages += '• {!! json_encode($error) !!}\n';
            @endforeach
            Swal.fire({
                icon: 'error',
                title: '⚠️ Validasi Gagal!',
                text: errorMessages,
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif