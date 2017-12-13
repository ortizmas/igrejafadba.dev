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
                                <?php $recurso = $modulo->getRecursos($modulo->modulo, $order='recurso.controlador'); ?>
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
                                            <?php foreach($recurso as $row): ?>
                                                <?php $key_upd = MyFunction::setKey($row->id, 'upd_recurso'); ?>
                                                <?php $key_ina = MyFunction::setKey($row->id, 'inactivar_recurso'); ?>
                                                <?php $key_rea = MyFunction::setKey($row->id, 'reactivar_recurso'); ?>
                                                <?php $key_del = MyFunction::setKey($row->id, 'eliminar_recurso'); ?>
                                                <tr>
                                                    <td><?php echo $counter2; ?></td>                                
                                                    <td><?php echo empty($row->controlador) ? '' : $row->controlador; ?></td>
                                                    <td><?php echo empty($row->accion) ? '' : $row->accion; ?></td>
                                                    <td><?php echo $row->descripcion; ?></td>
                                                    <td><?php echo ($row->activo == $modulo::ACTIVO) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Bloqueado</span>'; ; ?></td>
                                                    <td>
                                                        <?php if(empty($recurso->custom) && Auth::user()->perfil_id != MyLib::getSuperUser()) { ?>
                                                            <?php echo MyFunction::buttonTable('Editar recurso', "", array('class'=>'btn-disabled'), 'warning', 'fa-edit'); ?>
                                                            <?php echo MyFunction::buttonTable('Bloquear recurso', "", array('class'=>'btn-disabled'), 'success', 'fa-flag'); ?>
                                                            <?php echo MyFunction::buttonTable('Eliminar recurso', "", array('class'=>'btn-disabled'), 'danger', 'fa-ban'); ?>
                                                        <?php } else { ?>                   
                                                            <?php echo MyFunction::buttonTable('Modificar recurso', "$key_upd/edit", null, 'warning', 'fa-edit'); ?>
                                                            <?php if($row->activo == MyLib::getActivo()) { ?>
                                                                <?php echo MyFunction::buttonTable('Bloquear recurso', "estado/inactivar/$key_ina", null, 'success', 'fa-flag'); ?>
                                                            <?php } else { ?>
                                                                <?php echo MyFunction::buttonTable('Reactivar recurso', "estado/reactivar/$key_rea", null, 'danger', 'fa-flag'); ?>
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
@endsection

@section('script')

    <script src="{{ asset('js/script.js') }}"></script>

@endsection
