@extends('painel.layouts.painel')

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
                    <?php foreach($recursos as $recurso): ?>
                        <li class="<?php echo ($counter==1) ? 'active' : '';?>"><a href="<?php echo '#tab'.$counter; ?>" data-toggle="tab"><?php echo empty($recurso->modulo) ? 'Sin módulos' : MyFunction::ucfirst($recurso->modulo); ?></a></li>
                        <?php $counter++; ?>
                    <?php endforeach; ?>                
                </ul>

                <div class="tab-content">
                    <?php $counter = 1; ?>
                    <?php foreach($recursos as $modulo): ?>
                        <div class="tab-pane <?php echo ($counter==1) ? 'active' : '';?>" id="<?php echo 'tab'.$counter; ?>">
                            <?php $recurso = \App\Models\Recurso::hasRecurso($modulo->modulo, $order='recurso.controlador'); ?>
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
                                                <td><?php echo ($row->activo == \App\Models\Recurso::ACTIVO) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Bloqueado</span>'; ; ?></td>
                                                <td>
                                                    <?php if(empty($recurso->custom) && Auth::user()->perfil_id != \App\Models\Perfil::SUPER_USUARIO) { ?>
                                                        <?php echo MyFunction::buttonTable('Editar recurso', "", array('class'=>'btn-disabled'), 'warning', 'fa-edit'); ?>
                                                        <?php echo MyFunction::buttonTable('Bloquear recurso', "", array('class'=>'btn-disabled'), 'success', 'fa-flag'); ?>
                                                        <?php echo MyFunction::buttonTable('Eliminar recurso', "", array('class'=>'btn-disabled'), 'danger', 'fa-ban'); ?>
                                                    <?php } else { ?>                   
                                                        <?php echo MyFunction::buttonTable('Modificar recurso', "recurso/$key_upd/edit", null, 'warning', 'fa-edit'); ?>
                                                        <?php if($row->activo == \App\Models\Recurso::ACTIVO) { ?>
                                                            <?php echo MyFunction::buttonTable('Bloquear recurso', "recurso/estado/inactivar/$key_ina", null, 'success', 'fa-flag'); ?>
                                                        <?php } else { ?>
                                                            <?php echo MyFunction::buttonTable('Reactivar recurso', "recurso/estado/reactivar/$key_rea", null, 'danger', 'fa-flag'); ?>
                                                        <?php } ?>
                                                        <?php /*echo MyFunction::buttonTable('Eliminar recurso', url("painel/recurso/destroy", $key_del) , array('class'=>'js-confirm', 'data-role'=>'delete-item', 'msg-title'=>'Eliminar recurso', 'msg'=>'Está seguro de eliminar este recurso? <br />Recuerda que esta operación no se puede reversar.'), 'danger', 'fa-ban'); */?>  
                                                        {{-- <a href="{{ route('recurso.delete', $key_del) }}" class="btn js-url js-spinner js-link btn-small btn-danger js-confirm text-bold" data-role="delete-item"><i class="btn-icon-only fa fa-ban"></i></a>  --}}
                                                        <a  class="btn btn-danger" data-toggle="modal" data-target="#hapus "><i class="btn-icon-only fa fa-ban"></i></a>
                                                        
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
                <!-- /.box-header -->
                
          </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hapus" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>Apakah anda yakin menghapus data user <strong>{{$row->controlador}}</name> </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
       <a href="{{ route('recurso.delete', $key_del) }} "><button type="button" class="btn btn-primary">Excluir</button></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</section>
@endsection

@section('script')
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        $(document).on("click",'#deleteButton',(function(){

    var modulo = $(this).data('modulo');
    var id = $(this).data('id');

    $('#row_modulo').text(modulo);
    $('#row_id').text(id);
    $('#deleteModal').modal('show');
}));

$(document).on("click",'#deleteButtonConfirm',(function(){
    var id = $('#row_id').val();
    alert(id);
}));
    </script>
@endsection
