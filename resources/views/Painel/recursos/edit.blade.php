@extends('painel.layouts.painel')

@section('content')
    <div class="container shell-view">
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
                                Recursos del sistema | Actualizar recurso
                            </h4>
                        </div>
                        <div class="col-md-4" id="opt-process"></div>
                    </div>       
                </div>

                {!! Form::model($recurso, ['method' => 'PATCH', 'route' => ['recurso.update', $recurso->id]]) !!}

                {{-- {{ Form::model($recurso, ['files' => true,'id'=>'edit_form','class' =>'form-horizontal','accept-charset' => "UTF-8"]) }} --}}

                    <fieldset>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active x-big">
                                    <a href="#main_tab" aria-controls="main_tab" role="tab" data-toggle="tab">
                                        <i class="fa fa-file-text-o"></i>
                                        @if( $recurso->modulo!='')
                                            Edit {{ MyFunction::ucfirst($recurso->modulo) }}
                                        @elseif( $recurso->controlador!='')
                                            Edit {{ MyFunction::ucfirst($recurso->controlador) }}
                                        @endif
                                    </a>
                                </li>
                            </ul>
                    </fieldset>

                    <div class="col-md-4">
                        <div class='form-group'>
                             {!! Form::label('modulo', 'Módulo:') !!}
                             {!! Form::text('modulo', null, ['class' => 'form-control']) !!}
                        </div>
                        {{-- <div class='form-group'>
                             {!! Form::label('email', 'Email:') !!}
                             {!! Form::email('email', null, ['class' => 'form-control']) !!}
                        </div> --}}
                    </div>

                    <div class="col-md-4">
                        <div class='form-group'>
                             {!! Form::label('controlador', 'Controlador:') !!}
                             {!! Form::text('controlador', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class='form-group'>
                             {!! Form::label('accion', 'Accion:') !!}
                             {!! Form::text('accion', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="col-md-8">
                            <div class='form-group'>
                                 {!! Form::label('descripcion', 'Descripcion:') !!}
                                 {!! Form::textarea('descripcion', null, ['class' => 'form-control']) !!}
                            </div>
                           {{--  <div class='form-group'>
                                 {!! Form::label('city', 'City:') !!}
                                 {!! Form::text('city', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class='form-group'>
                                 {!! Form::label('state', 'State:') !!}
                                 {!! Form::text('state', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class='form-group'>
                                 {!! Form::label('zip', 'Zip:') !!}
                                 {!! Form::text('zip', null, ['class' => 'form-control']) !!}
                            </div> --}}
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="form-actions">
                            <a href="{{ route('recurso.index') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i> Voltar</a>
                            {!! Form::submit('Update Recurso', ['class' => 'btn btn-success text-bold']) !!}
                            {{ Form::reset('Limpar', ['class' => 'btn btn-danger text-bold']) }}
                        </div>
                    </div>
                    
                {{ Form::close() }}
        </div>
    </div>
@endsection
