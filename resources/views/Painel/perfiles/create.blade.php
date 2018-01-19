@extends('painel.layouts.painel')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Criar Tarefa</div>
                <?php 
                    if(session()->has('my_name'))
                         echo session()->get('my_name');
                    else
                        echo 'No data in the session';
                ?>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ isset($perfil) ? route('perfil.update', $perfil->id) : route('perfil.store') }}">
                        {{ csrf_field() }}

                        @if (isset($perfil))
                            {{ method_field('PUT') }}
                        @endif

                        <div class="form-group{{ $errors->has('rol') ? ' has-error' : '' }}">
                            <label for="rol" class="col-md-4 control-label">Rol</label>
                            <div class="col-md-6">
                                <input id="rol" type="text" class="form-control" name="rol" value="{{ isset($perfil) ? $perfil->rol : '' }}" required autofocus>

                                @if ($errors->has('rol'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rol') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('plantilla') ? ' has-error' : '' }}">
                            <label for="plantilla" class="col-md-4 control-label">Plantilla</label>

                            <div class="col-md-6">
                                <input id="plantilla" type="text" class="form-control" name="plantilla" value="{{ isset($perfil) ? $perfil->plantilla : old('plantilla') }}" required>

                                @if ($errors->has('plantilla'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('plantilla') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('activo') ? ' has-error' : '' }}">
                            <label for="activo" class="col-md-4 control-label">Estado</label>

                            <div class="col-md-6">
                                <select id="activo" name="activo" class="form-control" >
                                    @isset ($perfil)
                                        <option value="{{ $perfil->activo }}">{{ ($perfil->activo == 1) ? 'Ativo' : 'Inativo' }}</option>
                                    @endisset
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
                                <a href="{{ route('perfil.index') }}" class="btn btn-info">Listar</a>
                                <button type="submit" class="btn btn-success">
                                    {{ isset($perfil) ? 'Alterar Perfil' : 'Criar Perfil' }}
                                </button>
                                <input type="reset" name="reset" value="Cancelar" class="btn btn-danger">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
