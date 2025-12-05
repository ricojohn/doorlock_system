<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vite CSS\JS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- DataTables CSS --}}
  <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.5/b-3.2.5/b-html5-3.2.5/fc-5.0.5/r-3.0.7/sc-2.4.3/datatables.min.css" rel="stylesheet" integrity="sha384-Vyr4ebbeNw/Ff9Z74IMcr7/U7aJOsYpcAv2IQsne0THP+AWHAu/0Q2UMj0v3QCBb" crossorigin="anonymous">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Mar 09 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  @include('layout.header')
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  @include('layout.sidebar')
  <!-- End Sidebar-->

  <main id="main" class="main">
    <div class="container-fluid">
      <div class="container">
        @yield('content')
      </div>
    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

  {{-- DataTables JS --}}
  <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.5/b-3.2.5/b-html5-3.2.5/fc-5.0.5/r-3.0.7/sc-2.4.3/datatables.min.js" integrity="sha384-ZYBKAoKANbAU93vRC8S9dXOSbAanesVA8Vqk0ZE/SaK6Pr9MWUI1vWgj8Rc7vu+h" crossorigin="anonymous"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  {{-- jQuery --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  {{-- SweetAlert2 JS --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- Handle Flash Messages with SweetAlert2 --}}
  @if (session('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#4154f1',
        timer: 3000,
        timerProgressBar: true
      });
    </script>
  @endif

  @if (session('error'))
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#dc3545'
      });
    </script>
  @endif

  @if (session('warning'))
    <script>
      Swal.fire({
        icon: 'warning',
        title: 'Warning!',
        text: '{{ session('warning') }}',
        confirmButtonColor: '#ffc107'
      });
    </script>
  @endif

  @if (session('info'))
    <script>
      Swal.fire({
        icon: 'info',
        title: 'Info',
        text: '{{ session('info') }}',
        confirmButtonColor: '#0dcaf0'
      });
    </script>
  @endif

  @if (session('status'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('status') }}',
        confirmButtonColor: '#4154f1',
        timer: 3000,
        timerProgressBar: true
      });
    </script>
  @endif

  @if ($errors->any())
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: '<ul style="text-align: left; margin-top: 10px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        confirmButtonColor: '#dc3545'
      });
    </script>
  @endif

  @stack('scripts')
</body>

</html>