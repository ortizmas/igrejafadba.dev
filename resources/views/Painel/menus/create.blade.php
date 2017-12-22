@extends('painel.layouts.painel')

@section('content')
    <div class="container-fluid shell-view">
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
                <div class="page-header container-fluid">    
                    <div class="row">
                        <div class="col-md-8">
                            <h4>
                                Menu del sistema | Actualizar recurso
                            </h4>
                        </div>
                        <div class="col-md-4" id="opt-process"></div>
                    </div>       
                </div>

                {!! Form::open(['method' => 'POST', 'route' => ['menu.store'] , 'class' => 'js-validate js-remote form-vertical', 'data-to'=> 'shell-content', 'novalidate'=> 'novalidate']) !!}

                    <div class="row">
                        <div class="col-md-4">
                            <div class='form-group'>
                                {!! Form::label('nome', 'Nome menu:') !!}
                                {!! Form::text('nome', null, ['class' => 'form-control input-required', 'required'=> 'required']) !!}
                                <p class="help-block"><small class="help-error"></small></p>
                                @if ($errors->has('nome'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nome') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class='form-group'>
                                {!! Form::label('menu_id', 'Menú Pái:') !!}
                                {{-- {!! Form::select('menu_id', $menus->pluck('nome'), $menus->pluck('id'), ['class' => 'form-control input-required', 'required'=> 'required']) !!} --}}
                                <select class="form-control" name="menu_id">
                                    <option value=" ">Ninguno</option>
                                    @foreach($menus as $item)
                                      <option value="{{$item->id}}">{{$item->nome}}</option>
                                    @endforeach
                                </select>
                                 <p class="help-block"><small class="help-error"></small></p>
                                 @if ($errors->has('menu_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('menu_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class='form-group'>
                                {!! Form::label('recurso_id', 'Recurso:') !!}
                                {!! Form::select('recurso_id', array('' => 'Ninguno', $recursos), null, ['class' => 'form-control', 'id' => 'menu_recurso_id']) !!}
                                {{-- <select class="form-control" name="recurso_id" id="menu_recurso_id">
                                    @if ($menu)
                                        <option value="{{ $menu->id }}">{{ $menu->url }}</option>
                                    @else
                                        <option value="0">Ninguno</option>
                                    @endif
                                    
                                    @foreach($recursos as $recurso)
                                      <option value="{{$recurso->id}}">{{$recurso->recurso }}</option>
                                    @endforeach
                                </select> --}}
                            </div>
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="col-md-4">
                            <div class='form-group'>
                                {!! Form::label('posicion', 'Posicion:') !!}
                                {!! Form::text('posicion', null, ['class' => 'form-control input-required', 'required'=> 'required']) !!}
                                <p class="help-block"><small class="help-error"></small></p>
                                 @if ($errors->has('posicion'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('posicion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class='form-group'>
                                {!! Form::label('icono', 'Icono a mostrar:') !!}
                                {!! Form::text('icono', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class='form-group'>
                                {!! Form::label('url', 'Url:') !!}
                                {!! Form::text('url', null, ['class' => 'form-control input-required', 'required'=> 'required', 'id' => 'menu_url']) !!}
                                <p class="help-block"><small class="help-error"></small></p>
                                @if ($errors->has('url'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class='form-group'>
                                {!! Form::label('visibilidad', 'Visibilidade:') !!}
                                {!! Form::select('visibilidad', array('1' => 'Backend', '2' => 'Frontend'), '1', ['class' => 'form-control input-required', 'required'=> 'required']) !!}
                                <p class="help-block"><small class="help-error"></small></p>
                                 @if ($errors->has('visibilidad'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('visibilidad') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="form-actions">
                            <a href="{{ route('menu.index') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i> Voltar</a>
                            {!! Form::submit('Criar Recurso', ['class' => 'btn btn-success text-bold']) !!}
                            {{ Form::reset('Limpar', ['class' => 'btn btn-danger text-bold']) }}
                        </div>
                    </div>
                    
                {{ Form::close() }}
        </div>
    </div>
@endsection

@section('script')
    <!-- Para validar formulario exemplo em view lista -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
    <script type="text/javascript" >
        $(function(){
            $("#menu_recurso_id").on('change',function(){
                var recurso = $('option:selected',this).text();
                $("#menu_url").val(recurso.replace('/*','/'));
            });
        });
    </script>
@endsection
