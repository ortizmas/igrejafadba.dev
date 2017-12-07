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
                                Recursos del sistema | Actualizar recurso
                            </h4>
                        </div>
                        <div class="col-md-4" id="opt-process"></div>
                    </div>       
                </div>

                {!! Form::open(['method' => 'POST', 'route' => ['recurso.store'] , 'class' => 'js-validate js-remote form-vertical', 'data-to'=> 'shell-content', 'novalidate'=> 'novalidate']) !!}

                {{-- {{ Form::model($recurso, ['files' => true,'id'=>'edit_form','class' =>'form-horizontal','accept-charset' => "UTF-8"]) }} --}}

                    <div class="col-md-4">
                        <div class='form-group'>
                             {!! Form::label('modulo', 'Módulo:') !!}
                             {!! Form::text('modulo', null, ['class' => 'form-control']) !!}
                             @if ($errors->has('modulo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('modulo') }}</strong>
                                </span>
                            @endif
                        </div>
                        {{-- <div class='form-group'>
                             {!! Form::label('email', 'Email:') !!}
                             {!! Form::email('email', null, ['class' => 'form-control']) !!}
                        </div> --}}
                    </div>

                    <div class="col-md-4">
                        <div class='form-group'>
                             {!! Form::label('controlador', 'Controlador:') !!}
                             {!! Form::text('controlador', null, ['class' => 'form-control input-required', 'required'=> 'required']) !!}
                             <p class="help-block"><small class="help-error"></small></p>
                             @if ($errors->has('controlador'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('controlador') }}</strong>
                                </span>
                            @endif
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
                                 {!! Form::textarea('descripcion', null, ['class' => 'form-control input-required', 'required'=> 'required']) !!}
                                <p class="help-block"><small class="help-error"></small></p>
                                 @if ($errors->has('descripcion'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('descripcion') }}</strong>
                                    </span>
                                @endif
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
                            {!! Form::submit('Criar Recurso', ['class' => 'btn btn-success text-bold']) !!}
                            {{ Form::reset('Limpar', ['class' => 'btn btn-danger text-bold']) }}
                        </div>
                    </div>
                    
                {{ Form::close() }}
        </div>
    </div>

    <div class="container">
        <div class="panel panel-primary" style="width:750px;margin:0px auto">

          <div class="panel-heading">Bootstrap Validation example using validator.js</div>
          <div class="panel-body">

            <form data-toggle="validator" role="form">

              <div class="form-group">
                  <label class="control-label" for="inputName">Name</label>
                  <input class="form-control" data-error="Please enter name field." id="inputName" placeholder="Name"  type="text" required />
                  <div class="help-block with-errors"></div>
              </div>

              <div class="form-group">
                  <label class="control-label" for="inputSobrenome">Sobrenome</label>
                  <input class="form-control" data-error="Este campo é requerido." id="inputSobrenome" placeholder="Sobrenome"  type="text" required />
                  <div class="help-block with-errors"></div>
              </div>

              <div class="form-group">
                <label for="inputEmail" class="control-label">Email</label>
                <input type="email" class="form-control" id="inputEmail" placeholder="Email" required>
                <div class="help-block with-errors"></div>
              </div>

             {{--  <div class="form-group">
                <label for="inputPassword" class="control-label">Password</label>
                <div class="form-group">
                  <input type="password" data-minlength="5" class="form-control" id="inputPassword" data-error="must enter minimum of 5 characters" placeholder="Password" required>
                  <div class="help-block with-errors"></div>
                </div>
              </div> --}}

              <div class="form-group">
                  <label class="control-label" for="inputName">BIO</label>
                  <textarea class="form-control" data-error="Please enter BIO field." id="inputName" placeholder="Cina Saffary" required=""></textarea>
                  <div class="help-block with-errors"></div>
              </div>

                <div class="form-group has-feedback">
                    <label for="inputTwitter" class="control-label">Twitter</label>
                    <div class="input-group">
                        <span class="input-group-addon">@</span>
                        <input type="text" pattern="^[_A-z0-9]{1,}$" maxlength="15" class="form-control" id="inputTwitter" placeholder="1000hz" required>
                    </div>
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="control-label">Password</label>
                    <div class="form-inline row">
                      <div class="form-group col-sm-6">
                        <input type="password" data-minlength="6" class="form-control" id="inputPassword" placeholder="Password" required>
                        <div class="help-block">Minimum of 6 characters</div>
                      </div>
                      <div class="form-group col-sm-6">
                        <input type="password" class="form-control" id="inputPasswordConfirm" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm" required>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="radio">
                      <label>
                        <input type="radio" name="underwear" required>
                        Boxers
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio" name="underwear" required>
                        Briefs
                      </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" id="terms" data-error="Before you wreck yourself" required>
                        Check yourself
                      </label>
                      <div class="help-block with-errors"></div>
                    </div>
                </div>

              <div class="form-group">
                  <button class="btn btn-primary" type="submit">
                      Submit
                  </button>
              </div>
            </form>

          </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
@endsection
