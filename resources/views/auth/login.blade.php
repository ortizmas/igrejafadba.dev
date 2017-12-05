@extends('auth.templates.template')

@section('content')
<!-- /.login-logo -->
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>ADMIN</b>FADBA</a>
  </div>
  @if ( session('erro') )
        <div class="alert alert-danger">
            {{ session('erro') }}
        </div>
    @endif
      <div class="login-box-body">
        <p class="login-box-msg">Faça login para iniciar sua sessão</p>
        <form  method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required="">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
                <input id="password" type="password" name="password" class="form-control" placeholder="Password" required="">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-8">
                  <div class="checkbox icheck">
                    <label>
                      <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Lembre de mim
                    </label>
                  </div>
            </div>
                <!-- /.col -->
            <div class="col-xs-4">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div>
            {{-- <input id="rsa32_key" name="rsa32_key" type="hidden" value="{{ Helper::token() }}"> --}}
            {{ session('name') }}
            <?php /*Helper::token();*/ ?> 
                <!-- /.col -->
            </div>
        </form>

        <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
            Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
            Google+</a>
        </div>
        <!-- /.social-auth-links -->

        <a href="{{ route('password.request') }}">I forgot my password</a><br>
        <a href="register.html" class="text-center">Register a new membership</a>

      </div>
    </div>
  <!-- /.login-box-body -->
@endsection
