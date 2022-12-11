
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/adminlte.min.css') }}">
    @if(!empty($tempusdominus))
      <!-- Tempusdominus Bootstrap 4 -->
      <link rel="stylesheet" type="text/css" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    @endif
    @if(!empty($icheck))
      <!-- iCheck -->
      <link rel="stylesheet" type="text/css" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @endif
    @if(!empty($summernote))
      <!-- Summernote -->
      <link rel="stylesheet" type="text/css" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    @endif
    @if(!empty($select2))
      <!-- Select -->
      <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endif
    @if(!empty($datatables))
      <!-- DataTables -->
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">    
    @endif
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
    <!-- Custom styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    @stack('styles')
  </head>
  <body class="hold-transition sidebar-mini layout-fixed text-sm">
    <div class="wrapper">
      <!-- Header -->
      @include('site.header')
      <!-- Sidebar -->
      @include('site.sidebar')
      <!-- Content -->    
      @yield('content')
      <!-- Footer -->
      @include('site.footer')
      <!-- Timer -->
      @include('site.partials.timer')
      <!-- Error -->
      @if(Session::has('error'))
        <input type="hidden" id="error-content" value="{{ Session::get('error') }}">
      @endif
    </div>
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    @if(!empty($chartJS))
      <!-- ChartJS -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endif
    @if(!empty($momment))
      <!-- momment -->
      <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    @endif
    @if(!empty($tempusdominus))
      <!-- Tempusdominus Bootstrap 4 -->
      <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    @endif
    @if(!empty($summernote))
      <!-- Summernote -->
      <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    @endif
    @if(!empty($select2))
      <!-- Select2 -->
      <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    @endif
    @if(!empty($datatables))
      <!-- DataTables -->
      <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
      <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
      <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
      <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
      <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    @endif
    @if(!empty($progressbar))
      <script src="{{ asset('plugins/progress-bar/progressbar.js' ) }}"></script>        
    @endif
    <!-- Custom scripts -->
    <script src="{{ asset('js/timer.js') }}"></script>
    <script src="{{ asset('js/error.js') }}"></script>
    @if(!empty($toaster))
      <script src="{{ asset('js/toastr.js') }}"></script>      
    @endif
    @stack('scripts')
  </body>
</html>
