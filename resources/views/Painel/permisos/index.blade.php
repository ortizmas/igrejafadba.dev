@extends('painel.layouts.painel')

@section('content')
    <section class="container-fluid">

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
                            <a href="{{ route('permiso.create') }}" class="btn btn-primary pull-right" style="margin-top: -8px;">Criar novo Permiso</a>
                        </h4>
                    </div>

                    {!! Form::open(['method' => 'POST', 'route' => ['permiso.store'] , 'class' => 'js-validate js-remote form-vertical', 'data-to'=> 'shell-content', 'novalidate'=> 'novalidate']) !!}

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
                                    <?php $recurso = MyLib::hasRecurso($modulo->modulo, $order='recurso.controlador'); ?>
                                    <table class="table table-bordered table-hover table-striped table-condensed table-responsive" data-toggle="dataTable" data-form="deleteForm">
                                        <thead>
                                            <tr>                      
                                                <th rowspan="2" class="text-middle">RECURSO</th>
                                                <th rowspan="2" class="text-middle">DESCRIÇÃO</th>
                                                <th colspan="<?php echo count($perfiles); ?>" class="text-center">PERFILES</th>
                                            </tr>
                                            <tr>
                                                <?php foreach ($perfiles as $perfil) { ?>
                                                    <th class="text-center"><?php echo $perfil->rol; ?></th>
                                                <?php } ?>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            <?php if($recurso) { ?>
                                                <?php foreach($recurso as $row) : ?>
                                                <tr>
                                                    <td><?php echo $row->recurso; ?></td>
                                                    <td><?php echo $row->descripcion; ?></td>
                                                    <?php foreach ($perfiles as $perfil) { ?>
                                                        
                                                        <td class="text-center">
                                                            <?php if (in_array("$perfil->id-$row->id", $privilegios)) { ?>
                                                                <?php $old_privilegios[] = $perfil->id.'-'.$row->id; ?>
                                                                <?php echo Form::checkbox('privilegios[]', $perfil->id . '-' . $row->id, TRUE); ?>
                                                                <?php /*echo Form::hidden('perfil_id[]', $perfil->id); */?>
                                                            <?php } else { ?>
                                                                <?php echo Form::checkbox('privilegios[]', $perfil->id . '-' . $row->id, NULL); ?>
                                                                <?php /*echo Form::hidden('perfil_id[]', $perfil->id); */?>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php $counter++; ?>
                            <?php endforeach; ?>
                        </div>

                        <?php if(!empty($old_privilegios)) { ?>
                            <?php echo Form::hidden('old_privilegios', join(',', $old_privilegios)); ?>
                        <?php } ?>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-actions">
                                    <a href="{{ route('permiso.index') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i> Voltar</a>
                                    {!! Form::submit('Guardar privilegios', ['class' => 'btn btn-success text-bold']) !!}
                                    {{ Form::reset('Limpar', ['class' => 'btn btn-danger text-bold']) }}
                                </div>
                            </div>
                        </div>
                    
                    {{ Form::close() }}

                </div>
            </div>
        </div>

        <!-- /.Modal para confirmação ao excluir row-->
        {!! Btn::delete() !!}
    </section>
@endsection