<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap 3.3.6 -->
  <link href="{{ asset('assets/painel/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  {{-- <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"> --}}
  <!-- Font Awesome -->
  <link href="{{ asset('assets/painel/fonts/font-awesome.min.css') }}" rel="stylesheet">
  {{-- <link rel="stylesheet" href="fonts/font-awesome.min.css"> --}}
  <!-- Ionicons -->
  <link href="{{ asset('assets/painel/fonts/ionicons.min.css') }}" rel="stylesheet">
  {{-- <link rel="stylesheet" href="fonts/ionicons.min.css"> --}}
  <!-- Theme style -->
  <link href="{{ asset('assets/painel/dist/css/AdminLTE.min.css') }}" rel="stylesheet">
  {{-- <link rel="stylesheet" href="dist/css/AdminLTE.min.css"> --}}
  <!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
     <link href="{{ asset('assets/painel/dist/css/skins/_all-skins.min.css') }}" rel="stylesheet">
     {{-- <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css"> --}}
    <link href="{{ asset('assets/painel/plugins/iCheck/square/blue.css') }}" rel="stylesheet">
     <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
     <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="hold-transition login-page">

  
  @yield('content')




 <!-- jQuery 2.2.3 -->
 

{{--  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
 <!-- Bootstrap 3.3.6 -->
 <script src="bootstrap/js/bootstrap.min.js"></script>
 <!-- SlimScroll -->
 <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
 <!-- FastClick -->
 <script src="plugins/fastclick/fastclick.js"></script>
 <!-- AdminLTE App -->
 <script src="dist/js/app.min.js"></script>
 <!-- AdminLTE for demo purposes -->
 <script src="dist/js/demo.js"></script> --}}
 <script src="{{ asset('assets/painel/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<script src="{{ asset('assets/painel/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/painel/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/painel/plugins/fastclick/fastclick.js') }}"></script>
<script src="{{ asset('assets/painel/dist/js/app.min.js') }}"></script>


<script src="{{ asset('js/notify.min.js') }}"></script>
<script src="{{ asset('js/bootbox.min.js') }}"></script>
{{-- <script src="{{ asset('dist/js/demo.js') }}"></script> --}}
<script src="{{ asset('assets/painel/plugins/iCheck/icheck.min.js') }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>

@yield('script')

</body>
</html>
