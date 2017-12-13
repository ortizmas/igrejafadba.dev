@extends('painel.layouts.painel')

@section('title')
    {{ config('app.name', 'Fadba')}}
@endsection

@section('content')
    <section class="content">
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
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Contact list
                            <a href="{{ route('recurso.create') }}" class="btn btn-primary pull-right" style="margin-top: -8px;">Criar novo {{ $model }}</a>
                        </h4>
                    </div>

                    <ul class="nav nav-tabs nav-justified">
                        <?php $counter = 1; ?>
                        <?php foreach($rec as $recurso): ?>
                            <li class="<?php echo ($counter==1) ? 'active' : '';?>"><a href="<?php echo '#tab'.$counter; ?>" data-toggle="tab"><?php echo empty($recurso['modulo']->modulo) ? 'Sin módulos' : MyFunction::ucfirst($recurso['modulo']->modulo); ?></a></li>
                            <?php $counter++; ?>
                        <?php endforeach; ?>                
                    </ul>
                    <div class="tab-content">
                        <?php $counter = 1; ?>
                        <?php foreach($rec as $modulo): ?>
                            <div class="tab-pane <?php echo ($counter==1) ? 'active' : '';?>" id="<?php echo 'tab'.$counter; ?>">
                                <?php /*$recurso = MyLib::hasRecurso($modulo->modulo, $order='recurso.controlador');*/ ?>
                                <table class="table table-bordered table-hover table-striped table-condensed table-responsive" data-toggle="dataTable" data-form="deleteForm">
                                    <thead>
                                        <tr>
                                            <th>NUM</th>                        
                                            <th data-order="controlador">CONTROLADOR</th>
                                            <th data-order="accion">ACCION</th>
                                            <th >DESCRIPCION</th>
                                            <th data-order="activo">ESTADO</th>
                                            <th class="btn-actions col-blocked text-center">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($recurso) { ?>
                                            <?php $counter2 = 1; ?>
                                            <?php foreach($modulo['recursos'] as $row): ?>
                                                <?php $key_upd = MyFunction::setKey($row->id, 'upd_recurso'); ?>
                                                <?php $key_ina = MyFunction::setKey($row->id, 'inactivar_recurso'); ?>
                                                <?php $key_rea = MyFunction::setKey($row->id, 'reactivar_recurso'); ?>
                                                <?php $key_del = MyFunction::setKey($row->id, 'eliminar_recurso'); ?>
                                                <tr>
                                                    <td><?php echo $counter2; ?></td>                                
                                                    <td><?php echo empty($row->controlador) ? '' : $row->controlador; ?></td>
                                                    <td><?php echo empty($row->accion) ? '' : $row->accion; ?></td>
                                                    <td><?php echo $row->descripcion; ?></td>
                                                    <td><?php echo ($row->activo == MyLib::getActivo()) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Bloqueado</span>'; ; ?></td>
                                                    <td>
                                                        <?php if(empty($recurso->custom) && Auth::user()->perfil_id != MyLib::getSuperUser()) { ?>
                                                            <?php echo MyFunction::buttonTable('Editar recurso', "", array('class'=>'btn-disabled'), 'warning', 'fa-edit'); ?>
                                                            <?php echo MyFunction::buttonTable('Bloquear recurso', "", array('class'=>'btn-disabled'), 'success', 'fa-flag'); ?>
                                                            <?php echo MyFunction::buttonTable('Eliminar recurso', "", array('class'=>'btn-disabled'), 'danger', 'fa-ban'); ?>
                                                        <?php } else { ?>                   
                                                            <?php echo MyFunction::buttonTable('Modificar recurso', "recurso/$key_upd/edit", null, 'warning', 'fa-edit'); ?>
                                                            <?php if($row->activo == MyLib::getActivo()) { ?>
                                                                <?php echo MyFunction::buttonTable('Bloquear recurso', "recurso/estado/inactivar/$key_ina", null, 'success', 'fa-flag'); ?>
                                                            <?php } else { ?>
                                                                <?php echo MyFunction::buttonTable('Reactivar recurso', "recurso/estado/reactivar/$key_rea", null, 'danger', 'fa-flag'); ?>
                                                            <?php } ?>
                                                            
                                                            <a href="{{ route('recurso.delete', $key_del) }}" class="btn js-url js-spinner js-link btn-small btn-danger js-confirm text-bold form-delete" ><i class="btn-icon-only fa fa-ban"></i></a>
                                                            
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php $counter2++; ?>
                                            <?php endforeach; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php $counter++; ?>
                        <?php endforeach; ?>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- /.Modal para confirmação ao excluir row-->
        {!! Btn::delete() !!}
    </section>

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

