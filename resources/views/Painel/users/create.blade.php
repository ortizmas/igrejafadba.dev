@extends('painel.layouts.painel')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('success') }}
            </div>
        @endif
        @if (session('status'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('users.store') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('perfil_id') ? ' has-error' : '' }}">
                                <label for="perfil_id" class="col-md-4 control-label">Perfil</label>

                                <div class="col-md-6">
                                    <select name="perfil_id" class="form-control">
                                        @foreach ($perfils as $perfil)
                                            <option value="{{ $perfil->id }}">{{ $perfil->rol }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input id="perfil_id" type="perfil_id" class="form-control" name="perfil_id" value="{{ old('perfil_id') }}" required> --}}

                                    @if ($errors->has('perfil_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('perfil_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('activo') ? ' has-error' : '' }}">
                                <label for="activo" class="col-md-4 control-label">Estado</label>

                                <div class="col-md-6">
                                    <select id="activo" name="activo" class="form-control" >
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>

                                    @if ($errors->has('activo'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('activo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
