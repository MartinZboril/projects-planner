
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('dist/img/icon.png') }}">
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
      <link href="https://cdn.datatables.net/v/bs4/dt-1.13.3/b-2.3.5/r-2.4.0/datatables.min.css" rel="stylesheet"/>
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
      <x-timer.active-modal :timers="Auth::User()->activeTimers" modal-id="timers-preview-modal" />
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
      @if(!empty($doughnut))
        <script src="{{ asset('js/charts/doughnut.js') }}"></script>      
      @endif   
      @if(!empty($overview))
        <script src="{{ asset('js/charts/overview.js') }}"></script>      
      @endif            
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
    @if(!empty($progressbar))
      <script src="{{ asset('plugins/progress-bar/progressbar.js' ) }}"></script>    
      <script src="{{ asset('js/charts/progress_bar.js') }}"></script>      
    @endif
    @if(!empty($datatables))
      <!-- DataTables -->
      <script src="https://cdn.datatables.net/v/bs4/dt-1.13.3/b-2.3.5/r-2.4.0/datatables.min.js"></script>
    @endif    
    <!-- Custom scripts -->
    <script src="{{ asset('js/error.js') }}"></script>
    @if(!empty($toaster))
      <script src="{{ asset('js/toastr.js') }}"></script>      
    @endif
    @if(!empty($client))
      <script src="{{ asset('js/actions/client.js') }}"></script>  
    @endif
    @if(!empty($milestone))
      <script src="{{ asset('js/actions/milestone.js') }}"></script>  
    @endif
    @if(!empty($project))
      <script src="{{ asset('js/actions/project.js') }}"></script>  
    @endif
    @if(!empty($task))
      <script src="{{ asset('js/actions/task.js') }}"></script>  
    @endif
    @if(!empty($ticket))
      <script src="{{ asset('js/actions/ticket.js') }}"></script>  
    @endif
    @if(!empty($todo))
      <script src="{{ asset('js/actions/todo.js') }}"></script>  
    @endif   
    @if(!empty($comment))
      <script src="{{ asset('js/actions/comment.js') }}"></script>  
    @endif 
    @if(!empty($note))
      <script src="{{ asset('js/actions/note.js') }}"></script>  
    @endif       
    @if(!empty($kanban))
      <script src="{{ asset('js/actions/kanban.js') }}"></script>  
    @endif    
    @if(!empty($file))
      <script src="{{ asset('js/actions/file.js') }}"></script>  
    @endif    
    @if(!empty($rate))
      <script src="{{ asset('js/actions/rate.js') }}"></script>  
    @endif         
    @if(!empty($role))
      <script src="{{ asset('js/actions/role.js') }}"></script>  
    @endif                             
    <script src="{{ asset('js/timer.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('scripts')
  </body>
</html>
